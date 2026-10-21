<?php

namespace App\Ai;

use App\Support\IndexNow;
use App\Support\TranslatedSlug;
use Botble\Blog\Models\Post;
use Botble\Language\Facades\Language;
use Botble\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Translate a blog post with BlogAi and store it the way Botble's
 * language-advanced plugin does: posts_translations (title, description,
 * content), slugs_translations (URL) and the "<lang>_seo_meta" meta box.
 * The source is always the saved default-language (Spanish) version.
 */
class PostTranslator
{
    public function __construct(protected BlogAi $ai)
    {
    }

    /** Languages a post can be translated into: active, not the default, known to BlogAi. */
    public function languages(): array
    {
        if (! is_plugin_active('language') || ! is_plugin_active('language-advanced')) {
            return [];
        }
        $default = Language::getDefaultLocaleCode();

        return array_values(array_filter(
            LanguageAdvancedManager::getActiveLanguageCodes(),
            fn ($code) => $code !== $default && isset(BlogAi::LANGUAGES[$code])
        ));
    }

    /** @return list<string> languages that already have a translation */
    public function translated(Post $post): array
    {
        return DB::table('posts_translations')->where('posts_id', $post->getKey())->pluck('lang_code')->all();
    }

    /**
     * @return array{language: string, status: 'saved'|'skipped'}
     */
    public function translate(Post $post, string $language, string $provider, bool $overwrite = false, ?int $userId = null): array
    {
        if (! in_array($language, $this->languages(), true)) {
            throw new RuntimeException(trans('torns.ai_err_translate_language'));
        }
        // Posts written as separate per-language posts are not a translation source.
        $postLanguage = DB::table('language_meta')->where(['reference_id' => $post->getKey(), 'reference_type' => Post::class])->value('lang_meta_code');
        if ($postLanguage && $postLanguage !== Language::getDefaultLocaleCode()) {
            throw new RuntimeException(trans('torns.ai_err_translate_source'));
        }
        if (! $overwrite && in_array($language, $this->translated($post), true)) {
            return ['language' => $language, 'status' => 'skipped'];
        }

        // Straight from the table: the model may carry a translation in the admin.
        $row = DB::table('posts')->where('id', $post->getKey())->first(['name', 'description', 'content']);
        $seo = $this->seoMeta($post, 'seo_meta');
        $result = $this->ai->translate($provider, [
            'name' => $row->name ?? '',
            'description' => $row->description ?? '',
            'content' => $row->content ?? '',
            'seo_title' => $seo['seo_title'] ?? '',
            'seo_description' => $seo['seo_description'] ?? '',
        ], $language, $userId);

        DB::transaction(function () use ($post, $language, $result, $seo): void {
            DB::table('posts_translations')->updateOrInsert(
                ['lang_code' => $language, 'posts_id' => $post->getKey()],
                ['name' => $result['name'], 'description' => $result['description'], 'content' => $result['content']]
            );

            if ($result['seo_title'] !== '' || $result['seo_description'] !== '') {
                $this->saveSeoMeta($post, "{$language}_seo_meta", [
                    'seo_title' => $result['seo_title'],
                    'seo_description' => $result['seo_description'],
                ] + array_intersect_key($seo, ['index' => true]));
            }

            $this->saveSlug($post, $language, $result['name']);
        });

        app(IndexNow::class)->submitPostLater($post);
        // Its categories and tags in the same language, when they still have none.
        app(TaxonomyTranslator::class)->translatePostTaxonomy($post, $provider, [$language]);

        return ['language' => $language, 'status' => 'saved'];
    }

    protected function seoMeta(Post $post, string $key): array
    {
        $value = DB::table('meta_boxes')
            ->where(['meta_key' => $key, 'reference_id' => $post->getKey(), 'reference_type' => Post::class])
            ->value('meta_value');
        $value = json_decode((string) $value, true);

        return is_array($value) ? (array) ($value[0] ?? []) : [];
    }

    protected function saveSeoMeta(Post $post, string $key, array $value): void
    {
        $where = ['meta_key' => $key, 'reference_id' => $post->getKey(), 'reference_type' => Post::class];
        $exists = DB::table('meta_boxes')->where($where)->exists();
        $data = ['meta_value' => json_encode([$value]), 'updated_at' => now()];

        $exists
            ? DB::table('meta_boxes')->where($where)->update($data)
            : DB::table('meta_boxes')->insert($where + $data + ['created_at' => now()]);
    }

    /** URL in the translated language, unique within the blog prefix. */
    protected function saveSlug(Post $post, string $language, string $title): void
    {
        TranslatedSlug::save(Post::class, $post->getKey(), $language, $title);
    }
}
