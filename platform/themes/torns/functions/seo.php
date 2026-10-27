<?php

use App\Support\SiteContent;
use Botble\Base\Facades\BaseHelper;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Botble\Language\Facades\Language;
use Botble\Language\Models\LanguageMeta;
use Botble\Page\Models\Page;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Models\Slug;
use Botble\Theme\Events\RenderingSingleEvent;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Cache;

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
     * JSON-LD graph describes the same things once, with ids. Breadcrumbs stay,
     * except on the blog, where the theme writes its own.
     */
    function torns_strip_default_schemas(string $html): string
    {
        return (string) preg_replace_callback(
            '#<script[^>]*type="application/ld\+json"[^>]*>(.*?)</script>#s',
            function (array $match): string {
                $data = json_decode(html_entity_decode($match[1]), true);
                $type = is_array($data) ? ($data['@type'] ?? null) : null;

                // On posts, categories and tags the theme's graph carries the
                // breadcrumb (Inicio › Blog › …, translated): Botble's goes.
                $ours = $type === 'BreadcrumbList' && (Theme::get('post') || Theme::get('blogTaxonomy'));

                return (in_array($type, ['WebSite', 'Organization', 'NewsArticle', 'Article', 'BlogPosting'], true) || $ours) && empty($data['@id'])
                    ? ''
                    : $match[0];
            },
            $html
        );
    }
}

if (! function_exists('torns_social_meta')) {
    /**
     * Completes the share card that WhatsApp, Facebook, LinkedIn, X and
     * Telegram build from the page, on top of what Botble's SeoHelper prints:
     * - every page has an og:image (category pages had none: the blog
     *   service resets it), falling back to the theme's banner;
     * - og:image:width/height/type: without them Facebook and WhatsApp skip
     *   the picture the first time a link is shared, until they have
     *   fetched it in the background;
     * - og:image:secure_url and :alt, twitter:image and :alt;
     * - og:description and twitter:description cut to 200 characters at a
     *   word: longer ones are truncated mid-word by every network.
     */
    function torns_social_meta(string $html): string
    {
        $attr = fn (string $value) => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        $find = function (string $pattern) use ($html): ?string {
            return preg_match($pattern, $html, $m) ? html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8') : null;
        };

        $html = preg_replace_callback(
            '/(<meta (?:property="og:description"|name="twitter:description") content=")([^"]*)(")/u',
            function (array $m) use ($attr): string {
                $text = html_entity_decode($m[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if (mb_strlen($text) > 200) {
                    $text = mb_substr($text, 0, 199);
                    $text = rtrim(preg_replace('/\s+\S*$/u', '', $text) ?: $text, ' ,;:.-') . '…';
                }

                return $m[1] . $attr($text) . $m[3];
            },
            $html
        ) ?? $html;

        $image = $find('/<meta property="og:image" content="([^"]+)"/');
        $extra = [];
        if (! $image) {
            $image = Theme::asset()->url('images/og-banner.jpg');
            $extra[] = '<meta property="og:image" content="' . $attr($image) . '">';
        }

        $local = str_starts_with($image, rtrim(url('/'), '/') . '/') ? public_path(ltrim((string) parse_url($image, PHP_URL_PATH), '/')) : null;
        $size = $local ? Cache::remember('torns-og-size:' . md5($local . '@' . @filemtime($local)), now()->addDay(), function () use ($local) {
            $info = is_file($local) ? @getimagesize($local) : false;

            return $info ? [$info[0], $info[1], $info['mime']] : null;
        }) : null;

        $title = $find('/<meta property="og:title" content="([^"]*)"/') ?? (string) config('app.name');
        $extra[] = '<meta property="og:image:secure_url" content="' . $attr($image) . '">';
        if ($size) {
            $extra[] = '<meta property="og:image:type" content="' . $attr($size[2]) . '">';
            $extra[] = '<meta property="og:image:width" content="' . (int) $size[0] . '">';
            $extra[] = '<meta property="og:image:height" content="' . (int) $size[1] . '">';
        }
        $extra[] = '<meta property="og:image:alt" content="' . $attr($title) . '">';
        if (! str_contains($html, 'name="twitter:image"')) {
            $extra[] = '<meta name="twitter:image" content="' . $attr($image) . '">';
            $extra[] = '<meta name="twitter:image:alt" content="' . $attr($title) . '">';
        }

        $tags = implode(PHP_EOL, $extra) . PHP_EOL;
        $at = preg_match('/<meta property="og:image" content="[^"]*">\s*/', $html, $m, PREG_OFFSET_CAPTURE)
            ? $m[0][1] + strlen($m[0][0])
            : (preg_match('/<meta property="og:[^>]*>\s*/', $html, $m, PREG_OFFSET_CAPTURE) ? $m[0][1] : 0);

        return substr($html, 0, $at) . $tags . substr($html, $at);
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

/*
 * Posts, categories and tags have their URL translated (slugs_translations):
 * /ca/blog/sanitat. Botble also serves the Spanish key in another language
 * (/ca/blog/sanidad, the address before the translation) and the key of
 * another language, then redirects with a 302. Make it a 301, so search
 * engines move to the translated address for good. The query (?page=2)
 * is kept.
 */
add_filter(BASE_FILTER_PUBLIC_SINGLE_DATA, function ($slug) {
    if (! $slug instanceof Slug || ! in_array($slug->reference_type, [Post::class, Category::class, Tag::class], true)
        || ! request()->isMethod('GET') || is_in_admin()) {
        return $slug;
    }
    $model = $slug->reference_type::query()->find($slug->reference_id);
    $target = $model?->url;
    if (! $target) {
        return $slug;
    }
    $want = rawurldecode(rtrim((string) parse_url($target, PHP_URL_PATH), '/'));
    $have = rawurldecode(rtrim(request()->getPathInfo(), '/'));
    if ($want !== '' && $want !== $have) {
        $query = request()->getQueryString();
        throw new HttpResponseException(redirect()->to($target . ($query ? '?' . $query : ''), 301));
    }

    return $slug;
}, 1);

if (! function_exists('torns_blog_taxonomy_seo')) {
    /**
     * Title and description of a category or tag page in the current
     * language: its own SEO texts when it has them (<lang>_seo_meta, read by
     * Botble first), else «Sanitat · Blog de Torns» and its translated
     * description or a sentence about it. Tag pages are noindex, follow:
     * they repeat the categories' posts under a one-word title, so the
     * posts and the categories are what search engines should list.
     */
    function torns_blog_taxonomy_seo(Category|Tag $model, int $posts): void
    {
        $meta = (array) $model->getMetaData('seo_meta', true);
        if (empty($meta['seo_title'])) {
            SeoHelper::setTitle(__(':name: artículos para sanitarios a turnos · Blog de Torns', ['name' => $model->name]));
        }
        if (empty($meta['seo_description'])) {
            $description = trim(preg_replace('/\s+/', ' ', strip_tags((string) $model->description)));
            SeoHelper::setDescription($description !== '' ? $description : __(
                'Publicaciones del blog de Torns sobre :name, para profesionales sanitarios que trabajan a turnos y guardias.',
                ['name' => $model->name]
            ));
        }
        if ($model instanceof Tag) {
            SeoHelper::meta()->addMeta('robots', 'noindex, follow');
        }
    }
}

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
                // ->name, not pluck('name'): pluck skips the translated value.
                'articleSection' => $post->categories->map(fn ($category) => $category->name)->values()->all() ?: null,
                'keywords' => $post->tags->map(fn ($tag) => $tag->name)->implode(', ') ?: null,
                'wordCount' => str_word_count(strip_tags((string) $post->content)) ?: null,
            ]);
        }

        // Category and tag pages: a collection of posts, with its breadcrumb.
        $taxonomy = Theme::get('blogTaxonomy');
        $blog = function_exists('torns_blog_url') ? torns_blog_url() : url('blog');
        $home = rtrim(url($locale === 'es' ? '/' : $locale), '/');
        if ($taxonomy instanceof Category || $taxonomy instanceof Tag) {
            $graph[] = [
                '@type' => 'CollectionPage',
                '@id' => $current,
                'url' => $current,
                'name' => $taxonomy->name,
                'description' => SeoHelper::getDescription() ?: null,
                'inLanguage' => $language,
                'isPartOf' => ['@id' => "{$site}/#website"],
                'breadcrumb' => ['@id' => "{$current}#breadcrumb"],
            ];
            $graph[] = torns_breadcrumb("{$current}#breadcrumb", [
                [__('Inicio'), $home], [__('Blog'), $blog], [$taxonomy->name, $current],
            ]);
        }
        if ($post instanceof Post) {
            $category = $post->categories->first();
            $graph[] = torns_breadcrumb("{$post->url}#breadcrumb", array_values(array_filter([
                [__('Inicio'), $home],
                [__('Blog'), $blog],
                $category ? [$category->name, $category->url] : null,
                [$post->name, $post->url],
            ])));
        }

        return $graph;
    }
}

if (! function_exists('torns_breadcrumb')) {
    /** @param  list<array{0: string, 1: string}>  $items  name, URL */
    function torns_breadcrumb(string $id, array $items): array
    {
        return [
            '@type' => 'BreadcrumbList',
            '@id' => $id,
            'itemListElement' => array_map(fn (array $item, int $i) => [
                '@type' => 'ListItem', 'position' => $i + 1, 'name' => $item[0], 'item' => $item[1],
            ], $items, array_keys($items)),
        ];
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
