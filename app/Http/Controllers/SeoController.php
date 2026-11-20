<?php

namespace App\Http\Controllers;

use App\Support\IndexNow;
use App\Support\SiteIndex;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

/**
 * robots.txt, sitemap.xml, llms.txt, llms-full.txt, the Markdown version
 * of pages and posts, and the IndexNow key file (docs/seo-geo.md).
 */
class SeoController extends Controller
{
    /** AI search and assistant crawlers, named so their access is explicit. */
    protected const AI_AGENTS = [
        'OAI-SearchBot', 'ChatGPT-User', 'GPTBot',
        'Claude-SearchBot', 'Claude-User', 'ClaudeBot',
        'PerplexityBot', 'Perplexity-User',
        'Google-Extended', 'Applebot-Extended', 'Bingbot', 'DuckAssistBot', 'MistralAI-User',
    ];

    public function __construct(protected SiteIndex $index)
    {
    }

    public function robots(): Response
    {
        $sitemap = rtrim(url('/'), '/') . '/sitemap.xml';

        // Staging and local copies must never be indexed.
        if (! app()->isProduction()) {
            return $this->text("User-agent: *\nDisallow: /\n");
        }

        // The admin path is not listed: docs/INSTALL.md asks for a secret one.
        $rules = "Allow: /\n";

        $out = "# Torns — https://www.torns.app\n# Search engines and AI assistants may read and cite this site.\n\nUser-agent: *\n{$rules}";
        foreach (self::AI_AGENTS as $agent) {
            $out .= "\nUser-agent: {$agent}\n{$rules}";
        }

        return $this->text($out . "\nSitemap: {$sitemap}\n");
    }

    public function sitemap(): Response
    {
        return response($this->index->sitemap(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /** The per-language sitemaps of Botble listed other languages' pages: one sitemap now. */
    public function localeSitemap(): RedirectResponse
    {
        return redirect('/sitemap.xml', 301);
    }

    public function llms(): Response
    {
        return $this->text($this->index->llmsTxt(), 'text/markdown');
    }

    public function llmsFull(): Response
    {
        return $this->text($this->index->llmsFullTxt(), 'text/markdown');
    }

    /** /{path}.md and /{locale}/{path}.md */
    public function markdown(string $first, ?string $second = null): Response
    {
        $locales = $this->index->locales();
        [$locale, $path] = $second !== null && in_array($first, $locales, true)
            ? [$first, $second]
            : [$this->index->defaultLocale(), $second !== null ? "{$first}/{$second}" : $first];
        $path = $path === 'index' ? '' : $path;

        foreach ($this->index->pages() as $key => $page) {
            if ($page['slug'] === $path && isset($page['locales'][$locale]) && ($md = $this->index->pageMarkdown($key, $locale))) {
                return $this->text($md, 'text/markdown', $page['locales'][$locale]['url']);
            }
        }

        $post = $this->index->findPost($locale, $path);
        abort_unless($post, 404);
        $url = $this->index->url($locale, $path);

        return $this->text($this->index->postMarkdown($post, $url), 'text/markdown', $url);
    }

    /** IndexNow key file: /<key>.txt, the key as its only content. */
    public function indexNowKey(string $key, IndexNow $indexNow): Response
    {
        abort_unless($indexNow->enabled() && hash_equals($indexNow->key(), $key), 404);

        return response($key, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    protected function text(string $body, string $type = 'text/plain', ?string $canonical = null): Response
    {
        $headers = ['Content-Type' => "{$type}; charset=UTF-8"];
        if (! app()->isProduction()) {
            $headers['X-Robots-Tag'] = 'noindex, nofollow';
        }
        if ($canonical) {
            // The HTML page is the one to index; the Markdown is for readers and assistants.
            $headers['Link'] = '<' . $canonical . '>; rel="canonical"';
            $headers['X-Robots-Tag'] = 'noindex';
        }

        return response($body, 200, $headers);
    }
}
