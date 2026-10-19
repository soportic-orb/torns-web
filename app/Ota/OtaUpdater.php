<?php

namespace App\Ota;

use App\Support\SiteContent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use ZipArchive;

/**
 * Over-the-air updates of the theme and the site content.
 *
 * Every merge to main that touches the theme or content/site publishes a
 * GitHub release "ota-<version>" with three assets (see
 * .github/workflows/ota-release.yml):
 *
 *   ota-manifest.json      version, commit, sha256 of the bundle, roots
 *   ota-manifest.json.sig  Ed25519 signature of the manifest (base64)
 *   ota-bundle.zip         the files under the allowed roots
 *
 * check() looks for a newer release; apply() downloads it, verifies the
 * signature and the hash, backs up the current files and pages, swaps the
 * directories and syncs pages and menus (keeping edits made in /admin).
 * rollback() puts the last backup back.
 */
class OtaUpdater
{
    public const VERSION_KEY = 'torns_ota_version';
    public const AVAILABLE_KEY = 'torns_ota_available';
    public const HISTORY_KEY = 'torns_ota_history';
    public const CHECKED_KEY = 'torns_ota_checked_at';

    protected const MANIFEST = 'ota-manifest.json';
    protected const SIGNATURE = 'ota-manifest.json.sig';
    protected const BUNDLE = 'ota-bundle.zip';

    public function __construct(protected SiteContent $content)
    {
    }

    // ------------------------------------------------------------------ status

    public function currentVersion(): ?string
    {
        return setting(self::VERSION_KEY) ?: null;
    }

    /** @return array{version: string, tag: string, published_at: string, notes: string, url: string, assets: array<string, string>}|null */
    public function available(): ?array
    {
        $data = json_decode((string) setting(self::AVAILABLE_KEY, ''), true);

        return is_array($data) && ! empty($data['version']) && $data['version'] !== $this->currentVersion() ? $data : null;
    }

    public function history(): array
    {
        $data = json_decode((string) setting(self::HISTORY_KEY, '[]'), true);

        return is_array($data) ? $data : [];
    }

    public function lastCheck(): ?string
    {
        return setting(self::CHECKED_KEY) ?: null;
    }

    /** Problems that prevent applying updates on this server. @return string[] */
    public function requirements(): array
    {
        $problems = [];
        if (! class_exists(ZipArchive::class)) {
            $problems[] = 'The PHP zip extension is missing.';
        }
        if (! function_exists('sodium_crypto_sign_verify_detached')) {
            $problems[] = 'The PHP sodium extension is missing.';
        }
        if (! $this->publicKey()) {
            $problems[] = 'OTA_PUBLIC_KEY is not set in .env (see docs/OTA.md).';
        }
        foreach ($this->roots() as $root) {
            // Swapping a folder means renaming it inside its parent.
            if (! is_writable(dirname(base_path($root)))) {
                $problems[] = "The web server cannot write the folder that contains {$root}.";
            }
        }

        return $problems;
    }

    // ------------------------------------------------------------------ check

    /** Ask GitHub for the newest "ota-*" release and remember it. */
    public function check(): ?array
    {
        $res = $this->github()->get('https://api.github.com/repos/' . config('torns-ota.repository') . '/releases', ['per_page' => 20]);
        if ($res->failed()) {
            throw new RuntimeException('GitHub did not answer (' . $res->status() . ').');
        }

        $latest = null;
        foreach ((array) $res->json() as $release) {
            if (! empty($release['draft']) || ! empty($release['prerelease']) || ! str_starts_with((string) ($release['tag_name'] ?? ''), 'ota-')) {
                continue;
            }
            $assets = [];
            foreach ((array) ($release['assets'] ?? []) as $asset) {
                $assets[$asset['name']] = $asset['url'];
            }
            if (! isset($assets[self::MANIFEST], $assets[self::SIGNATURE], $assets[self::BUNDLE])) {
                continue;
            }
            if (! $latest || strcmp($release['published_at'], $latest['published_at']) > 0) {
                $latest = [
                    'version' => substr($release['tag_name'], 4),
                    'tag' => $release['tag_name'],
                    'published_at' => $release['published_at'],
                    'notes' => (string) ($release['body'] ?? ''),
                    'url' => (string) ($release['html_url'] ?? ''),
                    'assets' => $assets,
                ];
            }
        }

        setting()
            ->forceSet(self::AVAILABLE_KEY, $latest ? json_encode($latest) : '')
            ->forceSet(self::CHECKED_KEY, now()->toDateTimeString())
            ->save();

        return $this->available();
    }

    // ------------------------------------------------------------------ apply

    /**
     * @return array{version: string, report: array}
     */
    public function apply(?string $userName = null): array
    {
        if ($problems = $this->requirements()) {
            throw new RuntimeException(implode(' ', $problems));
        }
        $release = $this->available() ?? $this->check();
        if (! $release) {
            throw new RuntimeException('There is no new version to apply.');
        }

        return $this->locked(function () use ($release, $userName) {
            $work = storage_path('app/ota/work-' . $release['version']);
            File::deleteDirectory($work);
            File::ensureDirectoryExists($work);

            try {
                // 1. Download and verify.
                foreach ([self::MANIFEST, self::SIGNATURE, self::BUNDLE] as $name) {
                    $this->download($release['assets'][$name], "{$work}/{$name}");
                }
                $manifest = $this->verify($work);
                if (($manifest['version'] ?? null) !== $release['version']) {
                    throw new RuntimeException('The manifest is for another version.');
                }

                // 2. Unpack into a staging folder, refusing anything outside the roots.
                $staging = "{$work}/files";
                $this->unpack("{$work}/" . self::BUNDLE, $staging);

                // 3. Back up the current files and pages.
                $from = $this->currentVersion();
                $backup = $this->backup($from, $release['version']);

                // 4. Swap the directories and sync pages and menus.
                $this->swapIn($staging);
                $report = $this->content->sync();
                $this->clearCaches();
            } finally {
                File::deleteDirectory($work);
            }

            setting()->forceSet(self::VERSION_KEY, $release['version'])->save();
            $this->remember([
                'action' => 'apply',
                'version' => $release['version'],
                'from' => $from,
                'commit' => $manifest['commit'] ?? null,
                'at' => now()->toDateTimeString(),
                'by' => $userName,
                'backup' => basename($backup),
                'updated' => $report['updated'],
                'created' => $report['created'],
                'conflicts' => $report['conflicts'],
            ]);
            $this->pruneBackups();
            Log::info('Torns OTA applied', ['version' => $release['version'], 'from' => $from, 'by' => $userName, 'conflicts' => $report['conflicts']]);

            return ['version' => $release['version'], 'report' => $report];
        });
    }

    /** Put back the files and pages saved before the last update. */
    public function rollback(?string $userName = null): string
    {
        return $this->locked(function () use ($userName) {
            $backup = collect(File::directories(storage_path('app/ota/backups')))->sort()->last();
            if (! $backup) {
                throw new RuntimeException('There is no backup to go back to.');
            }
            $meta = json_decode((string) @file_get_contents("{$backup}/meta.json"), true) ?: [];

            $this->swapIn("{$backup}/files", keepOld: false);
            $snapshot = json_decode((string) @file_get_contents("{$backup}/pages.json"), true) ?: [];
            $this->content->restorePages($snapshot);
            $this->clearCaches();

            setting()->forceSet(self::VERSION_KEY, (string) ($meta['from'] ?? ''))->save();
            File::deleteDirectory($backup);

            $this->remember(['action' => 'rollback', 'version' => $meta['from'] ?? null, 'from' => $meta['to'] ?? null, 'at' => now()->toDateTimeString(), 'by' => $userName]);
            Log::info('Torns OTA rolled back', ['to' => $meta['from'] ?? null, 'by' => $userName]);

            return (string) ($meta['from'] ?? '');
        });
    }

    public function hasBackup(): bool
    {
        return is_dir(storage_path('app/ota/backups')) && File::directories(storage_path('app/ota/backups')) !== [];
    }

    // ------------------------------------------------------------------ steps

    protected function download(string $assetUrl, string $target): void
    {
        $res = $this->github()
            ->withHeaders(['Accept' => 'application/octet-stream'])
            ->timeout(300)
            ->sink($target)
            ->get($assetUrl);
        if ($res->failed() || ! is_file($target) || filesize($target) === 0) {
            throw new RuntimeException('Could not download ' . basename($target) . ' (' . $res->status() . ').');
        }
    }

    protected function verify(string $work): array
    {
        $manifestRaw = (string) file_get_contents("{$work}/" . self::MANIFEST);
        $signature = base64_decode(trim((string) file_get_contents("{$work}/" . self::SIGNATURE)), true);
        $key = $this->publicKey();

        if (! $signature || strlen($signature) !== SODIUM_CRYPTO_SIGN_BYTES
            || ! sodium_crypto_sign_verify_detached($signature, $manifestRaw, $key)) {
            throw new RuntimeException('The update signature is not valid: it was not published by the Torns release workflow.');
        }

        $manifest = json_decode($manifestRaw, true);
        if (! is_array($manifest) || ($manifest['format'] ?? null) !== 1) {
            throw new RuntimeException('Unknown manifest format.');
        }
        if (! hash_equals((string) ($manifest['sha256'] ?? ''), hash_file('sha256', "{$work}/" . self::BUNDLE))) {
            throw new RuntimeException('The downloaded bundle does not match the signed manifest.');
        }

        return $manifest;
    }

    protected function unpack(string $zipPath, string $staging): void
    {
        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) {
            throw new RuntimeException('The bundle is not a valid zip file.');
        }

        $present = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = (string) $zip->getNameIndex($i);
            $root = collect($this->roots())->first(fn (string $root) => str_starts_with($name, $root . '/'));
            if (! $root || str_contains($name, '..') || str_contains($name, '\\') || str_starts_with($name, '/')) {
                $zip->close();
                throw new RuntimeException("The bundle contains a file outside the allowed folders: {$name}");
            }
            $present[$root] = true;
        }
        // Every root must come complete: swapping in a missing one would delete it.
        foreach ($this->roots() as $root) {
            if (empty($present[$root])) {
                $zip->close();
                throw new RuntimeException("The bundle has no {$root} folder.");
            }
        }

        File::ensureDirectoryExists($staging);
        if (! $zip->extractTo($staging)) {
            $zip->close();
            throw new RuntimeException('Could not unpack the bundle.');
        }
        $zip->close();
    }

    protected function backup(?string $from, string $to): string
    {
        $dir = storage_path('app/ota/backups/' . now()->format('Ymd-His'));
        foreach ($this->roots() as $root) {
            if (is_dir(base_path($root))) {
                File::copyDirectory(base_path($root), "{$dir}/files/{$root}");
            }
        }
        File::put("{$dir}/pages.json", json_encode($this->content->snapshotPages(), JSON_UNESCAPED_UNICODE));
        File::put("{$dir}/meta.json", json_encode(['from' => $from, 'to' => $to, 'at' => now()->toDateTimeString()]));

        return $dir;
    }

    /**
     * Replace every root with its copy under $source. Each swap is a pair of
     * renames, so the site is never left with a half-copied theme; if one
     * fails, the roots already swapped are put back.
     */
    protected function swapIn(string $source, bool $keepOld = false): void
    {
        $done = [];
        try {
            foreach ($this->roots() as $root) {
                $new = "{$source}/{$root}";
                if (! is_dir($new)) {
                    continue;
                }
                $target = base_path($root);
                $old = $target . '.ota-old';
                File::deleteDirectory($old);
                if (is_dir($target) && ! @rename($target, $old)) {
                    throw new RuntimeException("Could not move {$root} aside.");
                }
                if (! @rename($new, $target)) {
                    // Different filesystem: copy instead of rename.
                    if (! File::copyDirectory($new, $target)) {
                        @rename($old, $target);
                        throw new RuntimeException("Could not put the new {$root} in place.");
                    }
                }
                $done[] = $root;
            }
        } catch (RuntimeException $e) {
            foreach (array_reverse($done) as $root) {
                $target = base_path($root);
                File::deleteDirectory($target);
                @rename($target . '.ota-old', $target);
            }

            throw $e;
        }

        if (! $keepOld) {
            foreach ($done as $root) {
                File::deleteDirectory(base_path($root) . '.ota-old');
            }
        }
    }

    protected function clearCaches(): void
    {
        foreach (['view:clear', 'cache:clear'] as $command) {
            try {
                Artisan::call($command);
            } catch (\Throwable $e) {
                Log::warning("Torns OTA: {$command} failed", ['error' => $e->getMessage()]);
            }
        }
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
    }

    protected function pruneBackups(): void
    {
        $dirs = collect(File::directories(storage_path('app/ota/backups')))->sort()->values();
        $keep = max(1, (int) config('torns-ota.keep_backups', 3));
        foreach ($dirs->slice(0, max(0, $dirs->count() - $keep)) as $dir) {
            File::deleteDirectory($dir);
        }
    }

    // ------------------------------------------------------------------ helpers

    protected function remember(array $entry): void
    {
        $history = $this->history();
        array_unshift($history, $entry);
        setting()->forceSet(self::HISTORY_KEY, json_encode(array_slice($history, 0, 20), JSON_UNESCAPED_UNICODE))->save();
    }

    protected function locked(callable $callback): mixed
    {
        $lock = Cache::lock('torns-ota', 900);
        if (! $lock->get()) {
            throw new RuntimeException('Another update is already running. Try again in a few minutes.');
        }
        try {
            @set_time_limit(600);

            return $callback();
        } finally {
            $lock->release();
        }
    }

    protected function roots(): array
    {
        return (array) config('torns-ota.roots');
    }

    protected function publicKey(): ?string
    {
        $key = base64_decode((string) config('torns-ota.public_key'), true);

        return $key !== false && strlen($key) === SODIUM_CRYPTO_SIGN_PUBLICKEYBYTES ? $key : null;
    }

    protected function github(): \Illuminate\Http\Client\PendingRequest
    {
        $http = Http::withHeaders(['Accept' => 'application/vnd.github+json', 'User-Agent' => 'torns-web-ota'])->timeout(30);
        if ($token = config('torns-ota.github_token')) {
            $http = $http->withToken($token);
        }

        return $http;
    }
}
