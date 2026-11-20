<?php

namespace App\Support;

use Botble\ACL\Models\User;
use Botble\Base\Facades\MetaBox;
use Botble\Language\Models\LanguageMeta;
use Botble\Menu\Models\Menu;
use Botble\Menu\Models\MenuLocation;
use Botble\Menu\Models\MenuNode;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use RuntimeException;

/**
 * Static pages and menus of the site, kept as files in content/site so they
 * can be versioned in git and shipped by OTA updates:
 *
 *   content/site/pages.json            page definitions (slug, template, names, SEO)
 *   content/site/pages/{key}.{lang}.html  page content (shortcodes or HTML)
 *   content/site/menus.json            menus with one label per language
 *
 * sync() writes them into the database. A page or menu that someone edited
 * in /admin after the last sync is left alone and reported as a conflict,
 * unless $force is true (first install). The fingerprint of what was last
 * written is stored in the "torns_site_content_state" setting. Blog posts
 * and categories are never touched.
 */
class SiteContent
{
    public const STATE_KEY = 'torns_site_content_state';

    public function __construct(protected ?string $root = null)
    {
        $this->root ??= base_path('content/site');
    }

    /**
     * @return array{updated: string[], created: string[], unchanged: string[], conflicts: string[]}
     */
    public function sync(bool $force = false): array
    {
        $report = ['updated' => [], 'created' => [], 'unchanged' => [], 'conflicts' => []];
        $state = $this->state();

        $this->syncPages($force, $state, $report);
        $this->syncMenus($force, $state, $report);

        setting()->forceSet(self::STATE_KEY, json_encode($state))->save();

        return $report;
    }

    /**
     * Current page contents, to restore them on rollback.
     *
     * @return array<string, array{id: int, name: string, content: string, template: string}>
     */
    public function snapshotPages(): array
    {
        $out = [];
        foreach (array_keys($this->definitions()['pages']) as $key) {
            foreach ($this->locales() as $locale) {
                if ($page = $this->findPage($key, $locale)) {
                    $out["{$key}.{$locale}"] = ['id' => $page->getKey(), 'name' => (string) $page->name, 'content' => (string) $page->content, 'template' => (string) $page->template];
                }
            }
        }

        return $out;
    }

    /** Put back page contents from snapshotPages() and forget their fingerprints. */
    public function restorePages(array $snapshot): void
    {
        $state = $this->state();
        foreach ($snapshot as $id => $row) {
            if ($page = Page::query()->find($row['id'])) {
                $page->forceFill(['name' => $row['name'], 'content' => $row['content'], 'template' => $row['template']])->save();
                $state['pages'][$id] = $this->pageFingerprint($page);
            }
        }
        setting()->forceSet(self::STATE_KEY, json_encode($state))->save();
    }

    // ------------------------------------------------------------------ pages

    protected function syncPages(bool $force, array &$state, array &$report): void
    {
        $userId = User::query()->value('id') ?? 1;

        foreach ($this->definitions()['pages'] as $key => $definition) {
            foreach ($this->locales() as $locale) {
                $id = "{$key}.{$locale}";
                $wanted = [
                    'name' => $definition['name'][$locale],
                    'content' => $this->pageContent($key, $locale),
                    'template' => $definition['template'],
                    'seo_title' => $definition['seo_title'][$locale] ?? '',
                    'seo_description' => $definition['seo_description'][$locale] ?? '',
                ];

                $page = $this->findPage($key, $locale);

                if ($page) {
                    $current = $this->pageFingerprint($page);
                    if ($current === $this->fingerprint($wanted)) {
                        $state['pages'][$id] = $current;
                        $report['unchanged'][] = "page {$id}";

                        continue;
                    }
                    // Edited in /admin since we last wrote it: keep the edit.
                    if (! $force && isset($state['pages'][$id]) && $state['pages'][$id] !== $current) {
                        $report['conflicts'][] = "page {$id}";

                        continue;
                    }
                }

                $created = ! $page;
                $page = $this->writePage($page, $key, $locale, $definition, $wanted, $userId);
                $state['pages'][$id] = $this->pageFingerprint($page);
                $report[$created ? 'created' : 'updated'][] = "page {$id}";
            }
        }

        setting()->save();
    }

    protected function writePage(?Page $page, string $key, string $locale, array $definition, array $wanted, int $userId): Page
    {
        $attributes = [
            'name' => $wanted['name'],
            'content' => $wanted['content'],
            'template' => $wanted['template'],
            'status' => 'published',
        ];

        if ($page) {
            $page->forceFill($attributes)->save();
        } else {
            $page = Page::query()->create($attributes + ['user_id' => $userId]);
        }

        LanguageMeta::query()->updateOrCreate(
            ['reference_id' => $page->getKey(), 'reference_type' => Page::class],
            ['lang_meta_code' => $locale, 'lang_meta_origin' => $this->origin($key)]
        );

        if ($definition['slug'] !== '') {
            Slug::query()->updateOrCreate(
                ['reference_id' => $page->getKey(), 'reference_type' => Page::class],
                ['key' => $definition['slug'], 'prefix' => '']
            );
        }

        MetaBox::saveMetaBoxData($page, 'seo_meta', [
            'seo_title' => $wanted['seo_title'],
            'seo_description' => $wanted['seo_description'],
            'index' => 'index',
        ]);

        // Theme options are language-aware: the default locale uses the bare
        // key, other locales get the locale segment in the key.
        $infix = $locale === $this->locales()[0] ? '' : "{$locale}-";
        $role = $definition['role'] ?? null;
        if ($role === 'homepage') {
            setting()->forceSet("theme-torns-{$infix}homepage_id", (string) $page->getKey());
        } elseif ($role === 'blog') {
            setting()->forceSet("theme-torns-{$infix}blog_page_id", (string) $page->getKey());
            if ($infix === '') {
                setting()->forceSet('blog_page_id', (string) $page->getKey());
            }
        }

        return $page;
    }

    protected function findPage(string $key, string $locale): ?Page
    {
        $meta = LanguageMeta::query()
            ->where('lang_meta_origin', $this->origin($key))
            ->where('lang_meta_code', $locale)
            ->where('reference_type', Page::class)
            ->first();

        return $meta ? Page::query()->find($meta->reference_id) : null;
    }

    protected function pageFingerprint(Page $page): string
    {
        $seo = (array) MetaBox::getMetaData($page, 'seo_meta', true);

        return $this->fingerprint([
            'name' => (string) $page->name,
            'content' => (string) $page->content,
            'template' => (string) $page->template,
            'seo_title' => (string) ($seo['seo_title'] ?? ''),
            'seo_description' => (string) ($seo['seo_description'] ?? ''),
        ]);
    }

    protected function pageContent(string $key, string $locale): string
    {
        $path = "{$this->root}/pages/{$key}.{$locale}.html";
        if (! is_file($path)) {
            throw new RuntimeException("Missing page content file content/site/pages/{$key}.{$locale}.html");
        }

        return rtrim((string) file_get_contents($path));
    }

    /** Same origin the original seeder used, so existing pages are matched. */
    protected function origin(string $key): string
    {
        return md5('torns-page-' . $key);
    }

    // ------------------------------------------------------------------ menus

    protected function syncMenus(bool $force, array &$state, array &$report): void
    {
        $path = "{$this->root}/menus.json";
        if (! is_file($path)) {
            return;
        }
        $menus = json_decode((string) file_get_contents($path), true)['menus'] ?? [];
        $default = $this->locales()[0];

        foreach ($menus as $slug => $definition) {
            foreach ($this->locales() as $locale) {
                $id = "{$slug}.{$locale}";
                $localizedSlug = $locale === $default ? $slug : "{$slug}-{$locale}";
                $prefix = $locale === $default ? '' : '/' . $locale;

                $items = [];
                foreach ($definition['items'] as $item) {
                    // A URL per language ({"es": …, "ca": …}) or one for all; an
                    // absolute one (another site, e.g. guia.torns.app) is kept as is.
                    $raw = is_array($item['url']) ? ($item['url'][$locale] ?? $item['url'][$default]) : $item['url'];
                    $url = match (true) {
                        (bool) preg_match('#^https?://#', $raw) => $raw,
                        $raw === '/' => $prefix ?: '/',
                        default => $prefix . $raw,
                    };
                    $items[] = ['title' => $item['label'][$locale] ?? $item['label'][$default], 'url' => $url];
                }

                $menu = Menu::query()->where('slug', $localizedSlug)->first();
                $current = $menu ? $this->menuFingerprint($menu) : null;
                if ($current !== null && $current === $this->fingerprint($items)) {
                    $state['menus'][$id] = $current;
                    $report['unchanged'][] = "menu {$id}";

                    continue;
                }
                if ($current !== null && ! $force && isset($state['menus'][$id]) && $state['menus'][$id] !== $current) {
                    $report['conflicts'][] = "menu {$id}";

                    continue;
                }

                $created = ! $menu;
                $menu ??= new Menu();
                $menu->forceFill([
                    'slug' => $localizedSlug,
                    'name' => $definition['name'] . ($locale === $default ? '' : ' (' . strtoupper($locale) . ')'),
                    'status' => 'published',
                ])->save();

                MenuLocation::query()->updateOrCreate(['menu_id' => $menu->getKey(), 'location' => $definition['location']]);
                LanguageMeta::query()->updateOrCreate(
                    ['reference_id' => $menu->getKey(), 'reference_type' => Menu::class],
                    ['lang_meta_code' => $locale, 'lang_meta_origin' => md5('torns-menu-' . $slug)]
                );

                MenuNode::query()->where('menu_id', $menu->getKey())->delete();
                foreach ($items as $position => $item) {
                    MenuNode::query()->create([
                        'menu_id' => $menu->getKey(),
                        'parent_id' => 0,
                        'title' => $item['title'],
                        'url' => $item['url'],
                        'position' => $position,
                        'target' => '_self',
                    ]);
                }

                $state['menus'][$id] = $this->menuFingerprint($menu);
                $report[$created ? 'created' : 'updated'][] = "menu {$id}";
            }
        }
    }

    protected function menuFingerprint(Menu $menu): string
    {
        $items = MenuNode::query()
            ->where('menu_id', $menu->getKey())
            ->orderBy('parent_id')
            ->orderBy('position')
            ->get(['title', 'url'])
            ->map(fn (MenuNode $node) => ['title' => (string) $node->title, 'url' => (string) $node->url])
            ->all();

        return $this->fingerprint($items);
    }

    // ------------------------------------------------------------------ helpers

    public function definitions(): array
    {
        $path = "{$this->root}/pages.json";
        $data = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;
        if (! is_array($data) || empty($data['pages'])) {
            throw new RuntimeException('content/site/pages.json is missing or invalid');
        }

        return $data;
    }

    /** @return string[] site languages, the default one first */
    public function locales(): array
    {
        return $this->definitions()['locales'];
    }

    protected function state(): array
    {
        $state = json_decode((string) setting(self::STATE_KEY, '{}'), true);

        return (is_array($state) ? $state : []) + ['pages' => [], 'menus' => []];
    }

    protected function fingerprint(array $data): string
    {
        // Line endings and trailing spaces do not count as an edit.
        array_walk_recursive($data, function (&$value): void {
            $value = rtrim(str_replace("\r\n", "\n", (string) $value));
        });

        return sha1(json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}
