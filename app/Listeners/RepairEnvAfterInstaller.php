<?php

namespace App\Listeners;

use Botble\Installer\Events\EnvironmentSaved;

/**
 * Botble's web installer rewrites .env from .env.example on the database
 * step, and only replaces the values that still hold Botble's own defaults.
 * With our .env.example that loses APP_KEY (never written, so the next
 * request fails with a 500), the database details just typed in the form,
 * and everything set by hand (APP_ENV=staging, ADMIN_DIR, OTA, AI, mail).
 *
 * Right after it saves, put those back: the key and the hand-set values come
 * from the environment this request booted with, the database and URL from
 * the installer form.
 */
class RepairEnvAfterInstaller
{
    /** Values set by hand that the installer must not drop. */
    protected const KEEP = [
        'APP_ENV', 'APP_DEBUG', 'ADMIN_DIR', 'MAIL_USERNAME', 'MAIL_PASSWORD',
        'OTA_PUBLIC_KEY', 'OTA_REPOSITORY', 'OTA_GITHUB_TOKEN', 'INSTALL_SEED_TOKEN',
        'TORNS_AI_ENABLED', 'TORNS_AI_DEFAULT_TEXT', 'TORNS_ANTHROPIC_API_KEY', 'TORNS_CLAUDE_MODEL',
        'TORNS_OPENAI_API_KEY', 'TORNS_OPENAI_TEXT_MODEL', 'TORNS_OPENAI_IMAGE_MODEL',
    ];

    public function handle(EnvironmentSaved $event): void
    {
        $path = base_path('.env');
        $content = @file_get_contents($path);
        if ($content === false) {
            return;
        }

        $request = $event->request;
        $key = (string) config('app.key');

        $values = [
            'APP_KEY' => $key !== '' ? $key : 'base64:' . base64_encode(random_bytes(32)),
            'APP_URL' => $request->input('app_url'),
            'DB_CONNECTION' => $request->input('database_connection'),
            'DB_HOST' => $request->input('database_hostname'),
            'DB_PORT' => $request->input('database_port'),
            'DB_DATABASE' => $request->input('database_name'),
            'DB_USERNAME' => $request->input('database_username'),
            'DB_PASSWORD' => $request->input('database_password'),
        ];
        foreach (self::KEEP as $name) {
            $current = env($name);
            if ($current !== null && $current !== '') {
                $values[$name] = is_bool($current) ? ($current ? 'true' : 'false') : (string) $current;
            }
        }

        foreach ($values as $name => $value) {
            if ($value === null) {
                continue;
            }
            $line = $name . '=' . $this->quote((string) $value);
            $pattern = '/^#?[ \t]*' . preg_quote($name, '/') . '=.*$/m';
            $content = preg_match($pattern, $content)
                ? preg_replace_callback($pattern, fn () => $line, $content, 1)
                : rtrim($content) . "\n" . $line . "\n";
        }

        @file_put_contents($path, $content);
    }

    protected function quote(string $value): string
    {
        return $value === '' || preg_match('/^[A-Za-z0-9_.:\/+=@-]+$/', $value)
            ? $value
            : '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $value) . '"';
    }
}
