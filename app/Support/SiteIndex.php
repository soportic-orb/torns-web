<?php

namespace App\Support;

use Botble\Blog\Models\Post;
use Botble\Language\Models\LanguageMeta;
use Botble\Page\Models\Page;
use DOMDocument;
use DOMNode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Everything search engines and AI assistants read about the site, built
 * from content/site and the published blog posts: language-correct URLs,
 * the sitemap entries (with hreflang alternates), llms.txt, llms-full.txt
 * and the Markdown version of pages and posts.
 *
 * Botble's own sitemap and llms.txt list every language's pages under every
 * language prefix (and llms-full.txt comes out empty), so these replace them.
 */
class SiteIndex
{
    protected const LABELS = [
        'es' => ['Publicado', 'Actualizado', 'Fuente'],
        'ca' => ['Publicat', 'Actualitzat', 'Font'],
        'gl' => ['Publicado', 'Actualizado', 'Fonte'],
        'eu' => ['Argitaratua', 'Eguneratua', 'Iturria'],
    ];

    public function __construct(protected SiteContent $content)
    {
    }

    /** @return string[] site languages, default first */
    public function locales(): array
    {
        return $this->content->locales();
    }

    public function defaultLocale(): string
    {
        return $this->locales()[0];
    }

    /** Absolute URL of a path in a language ("" is the home page). */
    public function url(string $locale, string $path = ''): string
    {
        $prefix = $locale === $this->defaultLocale() ? '' : '/' . $locale;
        $path = trim($path, '/');

        return rtrim(url('/'), '/') . $prefix . ($path !== '' ? '/' . $path : '');
    }

    // ------------------------------------------------------------------ pages

    /**
     * Static pages by key, each with its URL, name and SEO description per language.
     *
     * @return array<string, array{slug: string, markdown: bool, locales: array<string, array{url: string, name: string, description: string, updated: ?Carbon, md: ?string}>}>
     */
    public function pages(): array
    {
        $out = [];
        foreach ($this->content->definitions()['pages'] as $key => $definition) {
            $entry = ['slug' => $definition['slug'], 'markdown' => ! empty($definition['markdown']), 'locales' => []];
            foreach ($this->locales() as $locale) {
                $page = $this->findPage($key, $locale);
                if (! $page || (string) $page->status !== 'published') {
                    continue;
                }
                $url = $this->url($locale, $definition['slug']);
                $entry['locales'][$locale] = [
                    'url' => $url,
                    'name' => $definition['name'][$locale] ?? $definition['name'][$this->defaultLocale()],
                    'description' => $definition['seo_description'][$locale] ?? '',
                    'updated' => $page?->updated_at,
                    'md' => $entry['markdown'] ? $this->markdownUrl($locale, $definition['slug']) : null,
                ];
            }
            $out[$key] = $entry;
        }

        return $out;
    }

    public function markdownUrl(string $locale, string $slug): string
    {
        return $this->url($locale, ($slug !== '' ? $slug : 'index') . '.md');
    }

    /** Markdown kept in content/site/markdown for a page, if any. */
    public function pageMarkdown(string $key, string $locale): ?string
    {
        $path = base_path("content/site/markdown/{$key}.{$locale}.md");

        return is_file($path) ? (string) file_get_contents($path) : null;
    }

    protected function findPage(string $key, string $locale): ?Page
    {
        $meta = LanguageMeta::query()
            ->where('lang_meta_origin', md5('torns-page-' . $key))
            ->where('lang_meta_code', $locale)
            ->where('reference_type', Page::class)
            ->first();

        return $meta ? Page::query()->find($meta->reference_id) : null;
    }

    // ------------------------------------------------------------------ posts

    /**
     * Published posts, newest first, grouped by translation.
     *
     * @return Collection<int, array{origin: string, locales: array<string, array{post: Post, url: string, md: string}>}>
     */
    public function posts(int $limit = 500): Collection
    {
        $metas = LanguageMeta::query()
            ->where('reference_type', Post::class)
            ->get(['reference_id', 'lang_meta_code', 'lang_meta_origin'])
            ->keyBy('reference_id');

        $posts = Post::query()
            ->where('status', 'published')
            ->with('slugable')
            ->latest()
            ->limit($limit)
            ->get();

        $translations = Schema::hasTable('posts_translations')
            ? DB::table('posts_translations')->whereIn('posts_id', $posts->modelKeys())->get()->groupBy('posts_id')
            : collect();
        $slugKeys = Schema::hasTable('slugs_translations')
            ? DB::table('slugs_translations')->whereIn('slugs_id', $posts->pluck('slugable.id')->filter()->all())->get()
                ->mapWithKeys(fn ($row) => [$row->slugs_id . '.' . $row->lang_code => $row->key])
            : collect();

        $groups = [];
        foreach ($posts as $post) {
            $meta = $metas->get($post->getKey());
            $locale = $meta?->lang_meta_code ?: $this->defaultLocale();
            $origin = $meta?->lang_meta_origin ?: 'post-' . $post->getKey();
            $slug = $post->slugable ? trim(($post->slugable->prefix ? $post->slugable->prefix . '/' : '') . $post->slugable->key, '/') : null;
            if (! $slug || ! in_array($locale, $this->locales(), true)) {
                continue;
            }
            $groups[$origin] ??= ['origin' => $origin, 'locales' => []];
            $groups[$origin]['locales'][$locale] = [
                'post' => $post,
                'url' => $this->url($locale, $slug),
                'md' => $this->url($locale, $slug . '.md'),
            ];

            // Translations stored on the same post (language-advanced, as the AI translation does).
            foreach ($translations->get($post->getKey(), []) as $row) {
                if ($row->lang_code === $locale || ! in_array($row->lang_code, $this->locales(), true) || isset($groups[$origin]['locales'][$row->lang_code])) {
                    continue;
                }
                $translated = (clone $post)->forceFill(['name' => $row->name ?: $post->name, 'description' => $row->description, 'content' => $row->content ?: $post->content]);
                $key = $slugKeys->get($post->slugable->getKey() . '.' . $row->lang_code) ?: $post->slugable->key;
                $translatedSlug = trim(($post->slugable->prefix ? $post->slugable->prefix . '/' : '') . $key, '/');
                $groups[$origin]['locales'][$row->lang_code] = [
                    'post' => $translated,
                    'url' => $this->url($row->lang_code, $translatedSlug),
                    'md' => $this->url($row->lang_code, $translatedSlug . '.md'),
                ];
            }
        }

        return collect(array_values($groups));
    }

    /** The post published under {prefix}/{key} in a language, if any. */
    public function findPost(string $locale, string $path): ?Post
    {
        foreach ($this->posts() as $group) {
            $item = $group['locales'][$locale] ?? null;
            if ($item && $item['url'] === $this->url($locale, $path)) {
                return $item['post'];
            }
        }

        return null;
    }

    protected function localeOfUrl(string $url): string
    {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $first = explode('/', $path)[0] ?? '';

        return in_array($first, $this->locales(), true) ? $first : $this->defaultLocale();
    }

    public function postMarkdown(Post $post, string $url): string
    {
        $md = '# ' . $post->name . "\n\n";
        if ($post->description) {
            $md .= '> ' . trim(preg_replace('/\s+/', ' ', strip_tags((string) $post->description))) . "\n\n";
        }
        [$published, $updated, $source] = self::LABELS[$this->localeOfUrl($url)] ?? self::LABELS['es'];
        $md .= "{$published}: " . $post->created_at?->toDateString()
            . ($post->updated_at && $post->updated_at->ne($post->created_at) ? " · {$updated}: " . $post->updated_at->toDateString() : '')
            . " · {$source}: {$url}\n\n";

        return $md . $this->htmlToMarkdown((string) $post->content) . "\n";
    }

    // ------------------------------------------------------------------ outputs

    public function sitemap(): string
    {
        $entries = [];
        foreach ($this->pages() as $page) {
            $alternates = array_map(fn ($item) => $item['url'], $page['locales']);
            foreach ($page['locales'] as $item) {
                $entries[] = ['loc' => $item['url'], 'lastmod' => $item['updated'], 'alternates' => $alternates];
            }
        }
        foreach ($this->posts() as $group) {
            $alternates = array_map(fn ($item) => $item['url'], $group['locales']);
            foreach ($group['locales'] as $item) {
                $entries[] = ['loc' => $item['url'], 'lastmod' => $item['post']->updated_at, 'alternates' => $alternates];
            }
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";
        foreach ($entries as $entry) {
            $xml .= "  <url>\n    <loc>" . e($entry['loc']) . "</loc>\n";
            if ($entry['lastmod']) {
                $xml .= '    <lastmod>' . $entry['lastmod']->toAtomString() . "</lastmod>\n";
            }
            if (count($entry['alternates']) > 1) {
                foreach ($entry['alternates'] as $locale => $href) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . e($href) . "\"/>\n";
                }
                if (isset($entry['alternates'][$this->defaultLocale()])) {
                    $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="' . e($entry['alternates'][$this->defaultLocale()]) . "\"/>\n";
                }
            }
            $xml .= "  </url>\n";
        }

        return $xml . "</urlset>\n";
    }

    /** llms.txt (https://llmstxt.org): what Torns is and where to read more. */
    public function llmsTxt(): string
    {
        $default = $this->defaultLocale();
        $pages = $this->pages();
        $names = ['es' => 'Castellano', 'ca' => 'Català', 'gl' => 'Galego', 'eu' => 'Euskara'];

        $lines = ['# Torns', '', '> ' . $this->summary($default), ''];
        $lines[] = 'Torns es una app de Soportic (Octavi Rodríguez). Web: ' . $this->url($default) . ' · App web: https://app.torns.app · Soporte: soporte@torns.app · Organizaciones: hola@torns.app. Disponible en castellano, català, galego y euskara.';
        $lines[] = '';

        $lines[] = '## Producto';
        foreach (['funcionalidades', 'home', 'centros'] as $key) {
            if ($item = $pages[$key]['locales'][$default] ?? null) {
                $lines[] = "- [{$item['name']}]({$item['url']}): {$item['description']}";
                if ($item['md']) {
                    $lines[] = "- [{$item['name']} (Markdown)]({$item['md']}): la misma página en texto plano.";
                }
            }
        }
        $lines[] = '';

        $lines[] = '## Otros idiomas';
        foreach (array_slice($this->locales(), 1) as $locale) {
            $links = [];
            foreach (['home', 'funcionalidades', 'centros'] as $key) {
                if ($item = $pages[$key]['locales'][$locale] ?? null) {
                    $links[] = "[{$item['name']}]({$item['url']})" . ($item['md'] ? " ([Markdown]({$item['md']}))" : '');
                }
            }
            $lines[] = '- ' . ($names[$locale] ?? $locale) . ': ' . implode(' · ', $links);
        }
        $lines[] = '';

        $posts = $this->posts(60);
        if ($posts->isNotEmpty()) {
            $lines[] = '## Noticias y artículos';
            foreach ($posts as $group) {
                $item = $group['locales'][$default] ?? reset($group['locales']);
                $locale = array_search($item, $group['locales'], true);
                $description = trim(preg_replace('/\s+/', ' ', strip_tags((string) $item['post']->description)));
                $lines[] = "- [{$item['post']->name}]({$item['md']})" . ($locale !== $default ? " ({$locale})" : '') . ($description !== '' ? ": {$description}" : '');
            }
            $lines[] = '';
        }

        $lines[] = '## Legal y contacto';
        foreach (['contacta', 'privacidad', 'terminos'] as $key) {
            if ($item = $pages[$key]['locales'][$default] ?? null) {
                $lines[] = "- [{$item['name']}]({$item['url']})";
            }
        }
        $lines[] = '';
        $lines[] = '## Optional';
        $lines[] = '- [Texto completo](' . rtrim(url('/'), '/') . '/llms-full.txt): funcionalidades completas y los últimos artículos en Markdown.';

        return implode("\n", $lines) . "\n";
    }

    /** llms-full.txt: the full features page and the latest posts, as Markdown. */
    public function llmsFullTxt(): string
    {
        $default = $this->defaultLocale();
        $out = $this->pageMarkdown('funcionalidades', $default) ?? ('# Torns' . "\n\n" . $this->summary($default) . "\n");

        $posts = $this->posts(30);
        if ($posts->isNotEmpty()) {
            $out .= "\n\n---\n\n# Noticias y artículos de Torns\n";
            foreach ($posts as $group) {
                $item = $group['locales'][$default] ?? reset($group['locales']);
                $out .= "\n\n" . preg_replace('/^# /', '## ', $this->postMarkdown($item['post'], $item['url']));
            }
        }

        return rtrim($out) . "\n";
    }

    /** First paragraph of the features page: what Torns is, in one go. */
    public function summary(string $locale): string
    {
        $md = (string) $this->pageMarkdown('funcionalidades', $locale);
        foreach (preg_split('/\n{2,}/', $md) as $block) {
            $block = trim($block);
            if ($block !== '' && ! str_starts_with($block, '#')) {
                return preg_replace('/\s+/', ' ', $block);
            }
        }

        return 'Torns es la app de calendario laboral y cambios de turno para profesionales sanitarios en España.';
    }

    // ------------------------------------------------------------------ helpers

    /** Small HTML → Markdown converter for post bodies (the editor's tags). */
    public function htmlToMarkdown(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><div id="root">' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        $root = $doc->getElementById('root');

        $render = function (DOMNode $node, int $depth = 0) use (&$render): string {
            $out = '';
            foreach ($node->childNodes as $child) {
                if ($child->nodeType === XML_TEXT_NODE) {
                    $out .= preg_replace('/\s+/', ' ', $child->textContent);

                    continue;
                }
                if ($child->nodeType !== XML_ELEMENT_NODE) {
                    continue;
                }
                $tag = strtolower($child->nodeName);
                $inner = fn () => trim($render($child, $depth));
                $out .= match ($tag) {
                    'h1' => "\n\n## " . $inner() . "\n\n",
                    'h2' => "\n\n## " . $inner() . "\n\n",
                    'h3' => "\n\n### " . $inner() . "\n\n",
                    'h4', 'h5', 'h6' => "\n\n#### " . $inner() . "\n\n",
                    'p', 'div', 'section', 'figure' => "\n\n" . $inner() . "\n\n",
                    'br' => "  \n",
                    'strong', 'b' => '**' . $inner() . '**',
                    'em', 'i' => '*' . $inner() . '*',
                    'a' => '[' . $inner() . '](' . $child->getAttribute('href') . ')',
                    'img' => '![' . $child->getAttribute('alt') . '](' . $child->getAttribute('src') . ')',
                    'blockquote' => "\n\n> " . str_replace("\n", "\n> ", $inner()) . "\n\n",
                    'ul', 'ol' => "\n\n" . $this->listItems($child, $tag === 'ol', $depth, $render) . "\n\n",
                    'script', 'style' => '',
                    default => $render($child, $depth),
                };
            }

            return $out;
        };

        return trim(preg_replace("/\n{3,}/", "\n\n", $render($root)));
    }

    protected function listItems(DOMNode $list, bool $ordered, int $depth, callable $render): string
    {
        $lines = [];
        $n = 1;
        foreach ($list->childNodes as $item) {
            if (strtolower($item->nodeName) !== 'li') {
                continue;
            }
            $lines[] = str_repeat('  ', $depth) . ($ordered ? ($n++) . '. ' : '- ') . trim(preg_replace("/\n{2,}/", "\n", $render($item, $depth + 1)));
        }

        return implode("\n", $lines);
    }
}
