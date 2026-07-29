<?php

use Botble\Blog\Models\Post;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Events\RouteMatched;

/**
 * Default Open Graph image: used whenever the current page does not set its
 * own (posts set their featured image through the blog module).
 */
app('events')->listen(RouteMatched::class, function (): void {
    if (is_in_admin()) {
        return;
    }

    $ogImage = theme_option('seo_og_image');
    $ogImage = $ogImage ? RvMedia::getImageUrl($ogImage) : Theme::asset()->url('images/og-banner.jpg');

    SeoHelper::setImage($ogImage);
});

/**
 * JSON-LD structured data:
 * - Organization: every page.
 * - SoftwareApplication + FAQPage: homepage only.
 * - Article: single blog posts.
 */
add_filter(THEME_FRONT_FOOTER, function (?string $html): string {
    if (is_in_admin()) {
        return $html ?? '';
    }

    $schemas = [];

    $appStoreUrl = theme_option('app_store_url', 'https://apps.apple.com/es/app/torns/id6760354618');
    $playStoreUrl = theme_option('play_store_url', 'https://play.google.com/store/apps/details?id=com.tornsapp.android');

    $pageId = Theme::get('pageId');
    $isHomepage = $pageId && BaseHelper::isHomepage($pageId);

    // The page plugin already emits an Organization schema when rendering a
    // CMS page, so ours only fills the gap on non-page routes (blog, etc.).
    if (! $pageId) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Torns',
            'url' => rtrim(url('/'), '/'),
            'logo' => Theme::asset()->url('images/logo.png'),
            'email' => theme_option('contact_email', 'hola@torns.app'),
            'sameAs' => array_values(array_filter([$appStoreUrl, $playStoreUrl])),
        ];
    }

    if ($isHomepage) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            'name' => 'Torns',
            'operatingSystem' => 'iOS, Android',
            'applicationCategory' => 'BusinessApplication',
            'description' => 'App de cambios de turno para profesionales sanitarios con calendario unificado multi-centro y sincronización con Apple y Google Calendar.',
            'installUrl' => $appStoreUrl,
            'sameAs' => [$playStoreUrl],
            'offers' => [
                ['@type' => 'Offer', 'name' => 'Free', 'price' => '0', 'priceCurrency' => 'EUR'],
                ['@type' => 'Offer', 'name' => 'Pro Mensual', 'price' => '2.99', 'priceCurrency' => 'EUR'],
                ['@type' => 'Offer', 'name' => 'Pro Anual', 'price' => '29.90', 'priceCurrency' => 'EUR'],
            ],
        ];

        // FAQPage built from the same defaults the FAQ shortcode renders.
        $questions = [];
        foreach (torns_default_faq_items() as $item) {
            $questions[] = [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['answer']],
            ];
        }
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $questions,
        ];
    }

    $post = Theme::get('post');
    if ($post instanceof Post) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->name,
            'description' => (string) $post->description,
            'image' => $post->image ? RvMedia::getImageUrl($post->image) : null,
            'datePublished' => $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => 'Torns',
            ],
            'mainEntityOfPage' => $post->url,
        ];
    }

    $output = '';
    foreach ($schemas as $schema) {
        $schema = array_filter($schema, fn ($value) => $value !== null && $value !== '');
        $output .= '<script type="application/ld+json">'
            . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            . '</script>' . PHP_EOL;
    }

    return ($html ?? '') . $output;
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
