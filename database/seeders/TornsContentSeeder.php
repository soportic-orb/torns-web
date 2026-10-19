<?php

namespace Database\Seeders;

use App\Support\SiteContent;
use Botble\ACL\Models\User;
use Botble\Blog\Models\Category;
use Botble\Contact\Models\CustomField;
use Botble\Language\Models\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Slug\Models\Slug;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Initial content for the Torns website in the four site languages.
 *
 * Idempotent: every record is keyed by a stable identifier (slug + language,
 * or a lang_meta_origin hash), so the seeder can be re-run without duplicating
 * content. Existing rows are updated in place.
 *
 * Run with: php artisan db:seed --class=TornsContentSeeder
 */
class TornsContentSeeder extends Seeder
{
    protected const LOCALES = [
        'es' => ['name' => 'Español', 'flag' => 'es', 'default' => true],
        'ca' => ['name' => 'Català', 'flag' => 'es', 'default' => false],
        'gl' => ['name' => 'Galego', 'flag' => 'es', 'default' => false],
        'eu' => ['name' => 'Euskara', 'flag' => 'es', 'default' => false],
    ];

    public function run(): void
    {
        $this->seedLanguages();
        $this->seedSettings();
        $this->seedPagesAndMenus();
        $this->seedBlogCategories();
        $this->seedContactCustomField();

        // Theme translations may be cached per locale during the run.
        cache()->flush();

        $this->command?->info('Torns content seeded for the four site languages.');
    }

    /**
     * Translate a theme string into a specific locale, falling back to the
     * Spanish source text (keys are the Spanish literals).
     */
    protected function t(string $key, string $locale): string
    {
        static $lines = [];

        if ($locale === 'es') {
            return $key;
        }

        // The theme strings live in the theme's JSON files, which Laravel only
        // loads while the theme renders: read them directly.
        $lines[$locale] ??= json_decode((string) @file_get_contents(base_path("platform/themes/torns/lang/{$locale}.json")), true) ?: [];

        return $lines[$locale][$key] ?? $key;
    }

    protected function seedLanguages(): void
    {
        $order = 0;
        foreach (self::LOCALES as $code => $meta) {
            Language::query()->updateOrCreate(['lang_locale' => $code], [
                'lang_name' => $meta['name'],
                'lang_code' => $code,
                'lang_flag' => $meta['flag'],
                'lang_is_default' => $meta['default'],
                'lang_order' => $order++,
                'lang_is_rtl' => false,
            ]);
        }

        setting()->forceSet('language_hide_default', '1')->save();
    }

    protected function seedSettings(): void
    {
        $prefix = fn (string $model) => 'permalink-' . Str::slug(str_replace('\\', '_', $model));

        setting()
            ->forceSet('admin_title', 'Torns')
            ->forceSet('theme-torns-site_title', 'Torns')
            ->forceSet('theme-torns-seo_title', 'App de cambios de turno y calendario sanitario | Torns')
            ->forceSet('theme-torns-seo_description', 'Cambia turnos con tus compañeros del mismo centro y gestiona el calendario de todos tus centros con sincronización Apple y Google Calendar. Gratis, sin tarjeta.')
            ->forceSet($prefix(\Botble\Blog\Models\Post::class), 'blog')
            ->forceSet($prefix(Category::class), 'blog')
            ->forceSet($prefix(\Botble\Blog\Models\Tag::class), 'blog/tag')
            ->save();
    }

    /**
     * Pages and menus come from content/site (also shipped by OTA updates,
     * see docs/OTA.md). On a fresh install they are written as they are.
     */
    protected function seedPagesAndMenus(): void
    {
        $report = app(SiteContent::class)->sync(force: true);

        $this->command?->info(sprintf('Pages and menus: %d created, %d updated.', count($report['created']), count($report['updated'])));
    }

    /**
     * Base blog categories in the four languages. No sample posts are created:
     * content will be written from the admin panel.
     */
    protected function seedBlogCategories(): void
    {
        $userId = User::query()->value('id') ?? 1;

        $categories = [
            'novedades' => 'Novedades',
            'funcionalidades' => 'Funcionalidades',
            'sanidad' => 'Sanidad',
        ];

        foreach ($categories as $slug => $label) {
            $origin = md5('torns-category-' . $slug);

            foreach (array_keys(self::LOCALES) as $locale) {
                $existingMeta = LanguageMeta::query()
                    ->where('lang_meta_origin', $origin)
                    ->where('lang_meta_code', $locale)
                    ->where('reference_type', Category::class)
                    ->first();

                $attributes = [
                    'name' => $this->t($label, $locale),
                    'status' => 'published',
                    'author_id' => $userId,
                    'author_type' => User::class,
                ];

                $category = $existingMeta
                    ? tap(Category::query()->find($existingMeta->reference_id) ?? new Category(), fn (Category $model) => $model->forceFill($attributes)->save())
                    : Category::query()->create($attributes);

                LanguageMeta::query()->updateOrCreate(
                    ['reference_id' => $category->getKey(), 'reference_type' => Category::class],
                    ['lang_meta_code' => $locale, 'lang_meta_origin' => $origin]
                );

                Slug::query()->updateOrCreate(
                    ['reference_id' => $category->getKey(), 'reference_type' => Category::class],
                    ['key' => $slug, 'prefix' => 'blog']
                );
            }
        }
    }

    protected function seedContactCustomField(): void
    {
        if (! class_exists(CustomField::class)) {
            return;
        }

        CustomField::query()->updateOrCreate(
            ['name' => 'Centro de trabajo'],
            ['type' => 'text', 'required' => false, 'placeholder' => 'Nombre de tu centro (opcional)', 'order' => 0, 'status' => 'published']
        );
    }
}
