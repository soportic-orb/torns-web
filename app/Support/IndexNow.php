<?php

namespace App\Support;

use Botble\Blog\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * IndexNow: notifies search engines of new or changed URLs. The key is
 * generated once and kept in the settings table; it is published at
 * /<key>.txt (routes/seo-early.php), as the protocol requires.
 */
class IndexNow
{
    public const KEY_SETTING = 'torns_indexnow_key';

    public function __construct(protected SiteIndex $index)
    {
    }

    public function enabled(): bool
    {
        return (bool) config('indexnow.enabled', true) && app()->isProduction();
    }

    public function key(): string
    {
        $key = (string) setting(self::KEY_SETTING, '');
        if (! preg_match('/^[a-f0-9]{32}$/', $key)) {
            $key = bin2hex(random_bytes(16));
            setting()->forceSet(self::KEY_SETTING, $key)->save();
        }

        return $key;
    }

    /** The post's URL in every language it has, and the blog index. */
    public function postUrls(Post $post): array
    {
        foreach ($this->index->posts() as $group) {
            foreach ($group['locales'] as $item) {
                if ($item['post']->getKey() === $post->getKey()) {
                    return array_values(array_merge(
                        array_map(fn ($entry) => $entry['url'], $group['locales']),
                        array_map(fn ($locale) => $this->index->url($locale, 'blog'), array_keys($group['locales']))
                    ));
                }
            }
        }

        return [];
    }

    /** Every public URL of the site, as in the sitemap. */
    public function allUrls(): array
    {
        $urls = $this->pageUrls();
        foreach ($this->index->posts()->all() as $group) {
            foreach ($group['locales'] as $item) {
                $urls[] = $item['url'];
            }
        }
        foreach ($this->index->taxonomies() as $item) {
            array_push($urls, ...array_values($item['locales']));
        }

        return $urls;
    }

    /** The pages (content/site) in every language they are published in. */
    public function pageUrls(): array
    {
        $urls = [];
        foreach ($this->index->pages() as $page) {
            foreach ($page['locales'] as $item) {
                $urls[] = $item['url'];
            }
        }

        return $urls;
    }

    /**
     * After an OTA update, every page: the release may have changed their
     * texts (content/site) or how the theme draws them, and until now Bing
     * only heard about blog posts.
     */
    public function submitPagesLater(): void
    {
        if (! $this->enabled()) {
            return;
        }

        app()->terminating(fn () => $this->submit($this->pageUrls()));
    }

    /** After the response, so saving a post never waits for the search engines. */
    public function submitPostLater(Post $post): void
    {
        if (! $this->enabled() || (string) $post->status !== 'published') {
            return;
        }
        $postId = $post->getKey();

        app()->terminating(function () use ($postId): void {
            $post = Post::query()->find($postId);
            if ($post) {
                $this->submit($this->postUrls($post));
            }
        });
    }

    /**
     * @param  list<string>  $urls  absolute URLs of this site
     * @return bool|null null when there was nothing to send or it is off
     */
    public function submit(array $urls): ?bool
    {
        $host = (string) parse_url((string) config('app.url'), PHP_URL_HOST);
        $urls = array_values(array_unique(array_filter($urls, fn ($url) => parse_url($url, PHP_URL_HOST) === $host)));
        if (! $this->enabled() || $host === '' || ! $urls) {
            return null;
        }

        try {
            $key = $this->key();
            $response = Http::acceptJson()->timeout(10)->post((string) config('indexnow.endpoint'), [
                'host' => $host,
                'key' => $key,
                'keyLocation' => rtrim((string) config('app.url'), '/') . "/{$key}.txt",
                'urlList' => array_slice($urls, 0, 10000),
            ]);
            Log::info('Torns IndexNow', ['status' => $response->status(), 'urls' => count($urls)]);

            return $response->successful();
        } catch (Throwable $e) {
            Log::warning('Torns IndexNow failed', ['error' => $e->getMessage()]);

            return false;
        }
    }
}
