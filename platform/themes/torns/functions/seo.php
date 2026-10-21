<?php

use App\Support\SiteContent;
use Botble\Base\Facades\BaseHelper;
use Botble\Blog\Models\Post;
use Botble\Language\Facades\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Page\Models\Page;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Events\RenderingSingleEvent;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;

/*
 * SEO and AI readiness of the theme (docs/seo-geo.md):
 * - meta: default Open Graph image, large Twitter cards, og:locale, and
 *   noindex outside production (staging must never be indexed);
 * - one JSON-LD @graph per page with stable @ids (Organization, WebSite,
 *   MobileApplication, WebPage, FAQPage, BlogPosting), replacing the
 *   WebSite/Organization/NewsArticle snippets Botble prints on its own.
 */

defined('TORNS_SCHEMA_LOCALES') || define('TORNS_SCHEMA_LOCALES', ['es' => 'es-ES', 'ca' => 'ca-ES', 'gl' => 'gl-ES', 'eu' => 'eu-ES']);

app('events')->listen(RouteMatched::class, function (): void {
    if (is_in_admin()) {
        return;
    }

    $ogImage = theme_option('seo_og_image');
    $ogImage = $ogImage ? RvMedia::getImageUrl($ogImage) : Theme::asset()->url('images/og-banner.jpg');

    SeoHelper::setImage($ogImage);
    SeoHelper::twitter()->setType('summary_large_image');
    SeoHelper::openGraph()->addProperty('locale', str_replace('-', '_', TORNS_SCHEMA_LOCALES[torns_current_locale()] ?? 'es-ES'));

    if (! app()->isProduction()) {
        SeoHelper::meta()->addMeta('robots', 'noindex, nofollow');
    }
});

if (! function_exists('torns_current_locale')) {
    function torns_current_locale(): string
    {
        $locale = class_exists(Language::class) ? rescue(fn () => Language::getCurrentLocaleCode(), null, false) : null;

        return array_key_exists((string) $locale, TORNS_SCHEMA_LOCALES) ? (string) $locale : 'es';
    }
}

if (! function_exists('torns_strip_default_schemas')) {
    /**
     * Remove the JSON-LD blocks Botble adds by itself (WebSite, the page
     * Organization with an empty logo, the blog NewsArticle): the theme's
     * JSON-LD graph describes the same things once, with ids. Breadcrumbs stay.
     */
    function torns_strip_default_schemas(string $html): string
    {
        return (string) preg_replace_callback(
            '#<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>#s',
            function (array $match): string {
                $data = json_decode(html_entity_decode($match[1]), true);
                $type = is_array($data) ? ($data['@type'] ?? null) : null;

                return in_array($type, ['WebSite', 'Organization', 'NewsArticle', 'Article', 'BlogPosting'], true) && empty($data['@id'])
                    ? ''
                    : $match[0];
            },
            $html
        );
    }
}

if (! function_exists('torns_page_sibling')) {
    /** The same content/site page in another language (pages are one CMS page per language). */
    function torns_page_sibling(int|string $pageId, string $locale): ?int
    {
        $origin = LanguageMeta::query()->where('reference_type', Page::class)->where('reference_id', $pageId)->value('lang_meta_origin');
        $id = $origin ? LanguageMeta::query()
            ->where('reference_type', Page::class)
            ->where('lang_meta_origin', $origin)
            ->where('lang_meta_code', $locale)
            ->value('reference_id') : null;

        return $id ? (int) $id : null;
    }
}

/*
 * Pages are created one per language with the same slug (App\Support\SiteContent),
 * but Botble resolves /ca/funcionalidades by slug alone and found the Spanish
 * page. Serve the page of the current language instead, with its own content,
 * title and description.
 */
add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, function ($slug) {
    if (! $slug instanceof Slug || $slug->reference_type !== Page::class) {
        return $slug;
    }
    $sibling = torns_page_sibling($slug->reference_id, torns_current_locale());
    if ($sibling && $sibling !== (int) $slug->reference_id) {
        $slug = clone $slug;
        $slug->reference_id = $sibling;
    }

    return $slug;
}, 0);

// The home page renders the default-language page: use the SEO texts of the
// home page in the current language.
app('events')->listen(RenderingSingleEvent::class, function (RenderingSingleEvent $event): void {
    if ($event->slug->getKey() || is_in_admin() || ! ($homeId = BaseHelper::getHomepageId())) {
        return;
    }
    $locale = torns_current_locale();
    $page = ($id = torns_page_sibling($homeId, $locale)) ? Page::query()->find($id) : null;
    $meta = $page?->getMetaData('seo_meta', true);
    if (! empty($meta['seo_title'])) {
        SeoHelper::setTitle($meta['seo_title']);
    }
    if (! empty($meta['seo_description'])) {
        SeoHelper::setDescription($meta['seo_description']);
    }
});

if (! function_exists('torns_page_key')) {
    /** content/site key ("home", "funcionalidades"…) of a CMS page. */
    function torns_page_key(int|string $pageId): ?string
    {
        $origin = LanguageMeta::query()->where('reference_type', Page::class)->where('reference_id', $pageId)->value('lang_meta_origin');
        if (! $origin) {
            return null;
        }
        foreach (array_keys(rescue(fn () => app(SiteContent::class)->definitions()['pages'], [], false)) as $key) {
            if (md5('torns-page-' . $key) === $origin) {
                return $key;
            }
        }

        return null;
    }
}

if (! function_exists('torns_schema_graph')) {
    function torns_schema_graph(): array
    {
        $site = rtrim(url('/'), '/');
        $current = url()->current();
        $locale = torns_current_locale();
        $language = TORNS_SCHEMA_LOCALES[$locale];
        $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');
        $playStoreUrl = theme_option('play_store_url', 'https://play.google.com/store/apps/details?id=com.tornsapp.android');
        $languages = array_values(TORNS_SCHEMA_LOCALES);

        $graph = [
            [
                '@type' => 'Organization',
                '@id' => "{$site}/#organization",
                'name' => 'Torns',
                'legalName' => 'Soportic',
                'url' => $site,
                'logo' => ['@type' => 'ImageObject', 'url' => Theme::asset()->url('images/logo.png')],
                'email' => theme_option('contact_email', 'hola@torns.app'),
                'founder' => ['@type' => 'Person', 'name' => 'Octavi Rodríguez'],
                'contactPoint' => [
                    ['@type' => 'ContactPoint', 'contactType' => 'customer support', 'email' => 'soporte@torns.app', 'availableLanguage' => $languages],
                    ['@type' => 'ContactPoint', 'contactType' => 'sales', 'email' => 'hola@torns.app', 'availableLanguage' => $languages],
                ],
                'sameAs' => [$appStoreUrl, $playStoreUrl],
            ],
            [
                '@type' => 'WebSite',
                '@id' => "{$site}/#website",
                'url' => $site,
                'name' => 'Torns',
                'inLanguage' => $languages,
                'publisher' => ['@id' => "{$site}/#organization"],
            ],
            [
                '@type' => 'MobileApplication',
                '@id' => "{$site}/#app",
                'name' => 'Torns',
                'description' => __('Torns es la app de calendario laboral y cambios de turno para profesionales sanitarios en España: los turnos y guardias de todos tus centros en un solo calendario, y cambios de turno con tus compañeros.'),
                'applicationCategory' => 'BusinessApplication',
                'applicationSubCategory' => __('Calendario de turnos y cambios de turno'),
                'operatingSystem' => 'iOS, Android, watchOS, Web',
                'url' => $site,
                'installUrl' => $appStoreUrl,
                'downloadUrl' => [$appStoreUrl, $playStoreUrl],
                'sameAs' => [$appStoreUrl, $playStoreUrl, 'https://app.torns.app'],
                'inLanguage' => $languages,
                'screenshot' => [
                    Theme::asset()->url('images/home/screens/calendar-month-600.webp'),
                    Theme::asset()->url('images/home/screens/screen-2896-flat-480.webp'),
                    Theme::asset()->url('images/home/screens/work-stats-600.webp'),
                ],
                'featureList' => [
                    __('Calendario unificado de turnos y guardias de varios centros'),
                    __('Importar el cuadrante desde una foto o un documento'),
                    __('Cambios de turno entre compañeros del mismo centro'),
                    __('Cadenas de cambios entre 3 o 4 compañeros con IA'),
                    __('Modo SOS para cambios urgentes'),
                    __('Aviso por e-mail al gestor del cuadrante'),
                    __('Sincronización con Apple Calendar y Google Calendar'),
                    __('Estadísticas de horas trabajadas'),
                    __('Consultor legal sobre el convenio'),
                    __('Apple Watch y widgets'),
                ],
                'offers' => [
                    ['@type' => 'Offer', 'name' => 'Free', 'price' => '0', 'priceCurrency' => 'EUR'],
                    ['@type' => 'Offer', 'name' => 'Pro', 'price' => '2.99', 'priceCurrency' => 'EUR', 'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => '2.99', 'priceCurrency' => 'EUR', 'billingDuration' => 'P1M']],
                    ['@type' => 'Offer', 'name' => 'Pro', 'price' => '29.90', 'priceCurrency' => 'EUR', 'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => '29.90', 'priceCurrency' => 'EUR', 'billingDuration' => 'P1Y']],
                ],
                'publisher' => ['@id' => "{$site}/#organization"],
            ],
        ];

        $pageId = Theme::get('pageId');
        $post = Theme::get('post');

        if ($pageId) {
            $page = Page::query()->find($pageId);
            $key = torns_page_key($pageId);
            $isHome = BaseHelper::isHomepage($pageId);
            // Extra nodes kept with the page content (content/site/schema/<key>.<lang>.json).
            $schemaFile = $key ? base_path("content/site/schema/{$key}.{$locale}.json") : null;
            $hasSchema = $schemaFile && is_file($schemaFile);
            $graph[] = array_filter([
                '@type' => 'WebPage',
                '@id' => $current,
                'url' => $current,
                'name' => SeoHelper::getTitle(),
                'description' => SeoHelper::getDescription(),
                'inLanguage' => $language,
                'isPartOf' => ['@id' => "{$site}/#website"],
                'about' => in_array($key, ['home', 'funcionalidades'], true) || $isHome ? ['@id' => "{$site}/#app"] : null,
                'primaryImageOfPage' => SeoHelper::openGraph()->getProperty('image') ?: null,
                'dateModified' => $page?->updated_at?->toIso8601String(),
                'mainEntity' => $isHome || $hasSchema ? ['@id' => "{$current}#faq"] : null,
            ]);

            if ($isHome) {
                $graph[] = [
                    '@type' => 'FAQPage',
                    '@id' => "{$current}#faq",
                    'inLanguage' => $language,
                    'mainEntity' => array_map(fn (array $item) => [
                        '@type' => 'Question',
                        'name' => $item['question'],
                        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
                    ], torns_default_faq_items()),
                ];
            }

            if ($hasSchema && is_array($node = json_decode((string) file_get_contents($schemaFile), true))) {
                $graph[] = ['@id' => "{$current}#faq", 'inLanguage' => $language] + $node;
            }
        }

        if ($post instanceof Post) {
            $url = $post->url;
            $image = $post->image ? RvMedia::getImageUrl($post->image) : Theme::asset()->url('images/og-banner.jpg');
            $graph[] = [
                '@type' => 'WebPage',
                '@id' => $url,
                'url' => $url,
                'name' => $post->name,
                'inLanguage' => $language,
                'isPartOf' => ['@id' => "{$site}/#website"],
                'primaryImageOfPage' => $image,
            ];
            $graph[] = array_filter([
                '@type' => 'BlogPosting',
                '@id' => "{$url}#article",
                'mainEntityOfPage' => ['@id' => $url],
                'headline' => $post->name,
                'description' => trim(strip_tags((string) $post->description)) ?: null,
                'image' => [$image],
                'datePublished' => $post->created_at?->toIso8601String(),
                'dateModified' => ($post->updated_at ?? $post->created_at)?->toIso8601String(),
                'inLanguage' => $language,
                'author' => ['@id' => "{$site}/#organization"],
                'publisher' => ['@id' => "{$site}/#organization"],
                'isPartOf' => ['@id' => "{$site}/#website"],
                'about' => ['@id' => "{$site}/#app"],
                'articleSection' => $post->categories->pluck('name')->values()->all() ?: null,
                'keywords' => $post->tags->pluck('name')->implode(', ') ?: null,
                'wordCount' => str_word_count(strip_tags((string) $post->content)) ?: null,
            ]);
        }

        return $graph;
    }
}

add_filter(THEME_FRONT_FOOTER, function (?string $html): string {
    if (is_in_admin()) {
        return $html ?? '';
    }

    $schema = ['@context' => 'https://schema.org', '@graph' => torns_schema_graph()];

    return ($html ?? '') . '<script type="application/ld+json">'
        . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        . '</script>' . PHP_EOL;
}, 20);

/**
 * GA4: rendered only when a measurement ID is configured AND the app runs in
 * production — never on staging (nueva.torns.app uses APP_ENV=staging) or local.
 */
add_filter(THEME_FRONT_HEADER, function (?string $html): string {
    $measurementId = theme_option('ga4_measurement_id');

    if (! $measurementId || ! app()->isProduction() || is_in_admin()) {
        return $html ?? '';
    }

    $id = e($measurementId);

    return ($html ?? '') . <<<HTML
<script defer src="https://www.googletagmanager.com/gtag/js?id={$id}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '{$id}', { anonymize_ip: true });
</script>
HTML;
}, 20);
