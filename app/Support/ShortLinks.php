<?php

namespace App\Support;

use App\Models\ShortLink;
use Botble\Blog\Models\Post;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/**
 * Short links of blog posts: torns.app/<code>, four characters, one per
 * post and language (docs/short-links.md). Made from the post editor or the
 * first time a reader presses a share button; RedirectShortLinks follows them.
 */
class ShortLinks
{
    /** Lower-case letters and digits without the ones that look alike (0 o 1 l i). */
    public const ALPHABET = '23456789abcdefghjkmnpqrstuvwxyz';

    public const LENGTH = 4;

    /** First path segments the site uses or may use: never a code. */
    protected const RESERVED = ['blog', 'news', 'page', 'feed', 'tags', 'user', 'docs', 'help', 'api', 'app'];

    public function __construct(protected SiteIndex $index)
    {
    }

    public static function pattern(): string
    {
        return '/^[' . self::ALPHABET . ']{' . self::LENGTH . '}$/';
    }

    public function ready(): bool
    {
        return rescue(fn () => Schema::hasTable('torns_short_links'), false, false);
    }

    public function find(int $postId, string $locale): ?ShortLink
    {
        return $this->ready() ? ShortLink::query()->where(['post_id' => $postId, 'locale' => $locale])->first() : null;
    }

    /** The post's link in that language, made if it has none yet. Null if the post is not published in it. */
    public function forPost(Post $post, string $locale): ?ShortLink
    {
        if (! $this->ready() || ! $this->index->postUrl($post, $locale)) {
            return null;
        }
        if ($link = $this->find($post->getKey(), $locale)) {
            return $link;
        }

        for ($attempt = 0; $attempt < 20; $attempt++) {
            $code = $this->randomCode();
            if ($this->taken($code)) {
                continue;
            }
            try {
                return ShortLink::query()->create(['code' => $code, 'post_id' => $post->getKey(), 'locale' => $locale]);
            } catch (QueryException) {
                // Same code or same post and language made at the same time.
                if ($link = $this->find($post->getKey(), $locale)) {
                    return $link;
                }
            }
        }

        return null;
    }

    /** https://torns.app/wy89: the site's host without «www.». */
    public function url(ShortLink $link): string
    {
        $root = rtrim(url('/'), '/');

        return preg_replace('#^(https?://)www\.#', '$1', $root) . '/' . $link->code;
    }

    /** torns.app/wy89, to show. */
    public function label(ShortLink $link): string
    {
        return preg_replace('#^https?://#', '', $this->url($link));
    }

    /** Where a link goes now: the post in its language, or the blog if it is gone or unpublished. */
    public function target(ShortLink $link): string
    {
        $post = Post::query()->with('slugable')->find($link->post_id);

        return ($post ? $this->index->postUrl($post, $link->locale) : null)
            ?: $this->index->url($link->locale, 'blog');
    }

    public function resolve(string $code): ?ShortLink
    {
        return $this->ready() ? ShortLink::query()->where('code', $code)->first() : null;
    }

    public function countClick(ShortLink $link): void
    {
        ShortLink::query()->whereKey($link->getKey())->update([
            'clicks' => DB::raw('clicks + 1'),
            'last_clicked_at' => now(),
        ]);
    }

    protected function randomCode(): string
    {
        $code = '';
        for ($i = 0; $i < self::LENGTH; $i++) {
            $code .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
        }

        return $code;
    }

    /** In use as a code, a locale, a page or other top-level slug, or a route. */
    protected function taken(string $code): bool
    {
        if (in_array($code, self::RESERVED, true) || in_array($code, $this->index->locales(), true)) {
            return true;
        }
        if (ShortLink::query()->where('code', $code)->exists()) {
            return true;
        }
        if (DB::table('slugs')->where('key', $code)->where(fn ($query) => $query->whereNull('prefix')->orWhere('prefix', ''))->exists()) {
            return true;
        }
        if (Schema::hasTable('slugs_translations') && DB::table('slugs_translations')->where('key', $code)->exists()) {
            return true;
        }

        return collect(Route::getRoutes()->getRoutes())->contains(fn ($route) => strtok($route->uri(), '/') === $code);
    }
}
