<?php

namespace App\Support;

use Botble\Blog\Models\Category;
use Botble\Blog\Models\Tag;
use Botble\Language\Facades\Language;
use Illuminate\Support\Facades\DB;

/**
 * Turns the blog categories (and tags) written as one row per language
 * (language plugin v1: rows linked by language_meta.lang_meta_origin, as
 * TornsContentSeeder created them) into one row per category with its
 * translations (language-advanced v2), the way the blog reads them.
 *
 * For each group, the row in the default language stays. Every other row:
 *   1. its posts move to the default row (post_categories / post_tags),
 *      so no published post loses a category;
 *   2. its subcategories move under the default row;
 *   3. its name and description become the default row's translation for
 *      that language, unless one written by hand is already there;
 *   4. its URL becomes the default row's translated URL, when it differs;
 *   5. it is deleted, with its slug, language_meta and meta boxes.
 *
 * plan() only reads; apply() runs each group in a transaction.
 */
class TaxonomyMerge
{
    /** @return list<array{type: string, origin: string, keep: array, merge: list<array>}> */
    public function plan(): array
    {
        $default = $this->defaultLocale();
        $groups = [];
        foreach ([Category::class, Tag::class] as $type) {
            $metas = DB::table('language_meta')->where('reference_type', $type)->get()->groupBy('lang_meta_origin');
            foreach ($metas as $origin => $rows) {
                if ($rows->count() < 2) {
                    continue;
                }
                $table = (new $type())->getTable();
                $keepMeta = $rows->firstWhere('lang_meta_code', $default) ?? $rows->sortBy('reference_id')->first();
                $keep = DB::table($table)->where('id', $keepMeta->reference_id)->first();
                if (! $keep) {
                    continue;
                }
                $merge = [];
                foreach ($rows as $meta) {
                    if ((int) $meta->reference_id === (int) $keep->id) {
                        continue;
                    }
                    $row = DB::table($table)->where('id', $meta->reference_id)->first();
                    if (! $row) {
                        continue;
                    }
                    $merge[] = [
                        'id' => (int) $row->id,
                        'lang' => $meta->lang_meta_code,
                        'name' => $row->name,
                        'slug' => DB::table('slugs')->where(['reference_type' => $type, 'reference_id' => $row->id])->value('key'),
                        'posts' => $this->pivot($type)->where($this->pivotKey($type), $row->id)->count(),
                    ];
                }
                if ($merge) {
                    $groups[] = [
                        'type' => $type,
                        'origin' => (string) $origin,
                        'keep' => ['id' => (int) $keep->id, 'lang' => $keepMeta->lang_meta_code, 'name' => $keep->name],
                        'merge' => $merge,
                    ];
                }
            }
        }

        return $groups;
    }

    /** @return array{groups: int, rows: int, posts_moved: int} */
    public function apply(): array
    {
        $stats = ['groups' => 0, 'rows' => 0, 'posts_moved' => 0];
        foreach ($this->plan() as $group) {
            DB::transaction(function () use ($group, &$stats): void {
                $type = $group['type'];
                $keepId = $group['keep']['id'];
                $keepSlug = DB::table('slugs')->where(['reference_type' => $type, 'reference_id' => $keepId])->first();
                foreach ($group['merge'] as $item) {
                    $stats['posts_moved'] += $this->movePosts($type, $item['id'], $keepId);
                    if ($type === Category::class) {
                        DB::table('categories')->where('parent_id', $item['id'])->update(['parent_id' => $keepId]);
                    }
                    $this->keepTranslation($type, $item, $keepId);
                    $this->keepSlug($type, $item, $keepSlug);
                    $this->deleteRow($type, $item['id']);
                    $stats['rows']++;
                }
                $stats['groups']++;
            });
        }

        return $stats;
    }

    protected function movePosts(string $type, int $from, int $to): int
    {
        $key = $this->pivotKey($type);
        $postIds = $this->pivot($type)->where($key, $from)->pluck('post_id');
        $already = $this->pivot($type)->where($key, $to)->whereIn('post_id', $postIds)->pluck('post_id')->all();
        foreach ($postIds as $postId) {
            if (! in_array($postId, $already)) {
                $this->pivot($type)->insert(['post_id' => $postId, $key => $to]);
            }
        }
        $this->pivot($type)->where($key, $from)->delete();

        return $postIds->count();
    }

    protected function keepTranslation(string $type, array $item, int $keepId): void
    {
        [$table, $fk] = $type === Category::class ? ['categories_translations', 'categories_id'] : ['tags_translations', 'tags_id'];
        $source = DB::table((new $type())->getTable())->where('id', $item['id'])->first(['name', 'description']);
        $keepName = DB::table((new $type())->getTable())->where('id', $keepId)->value('name');
        $current = DB::table($table)->where(['lang_code' => $item['lang'], $fk => $keepId])->first();
        // A translation that differs from the default name was written by someone: it stays.
        if ($current && trim((string) $current->name) !== '' && trim((string) $current->name) !== trim((string) $keepName)) {
            return;
        }
        DB::table($table)->updateOrInsert(
            ['lang_code' => $item['lang'], $fk => $keepId],
            ['name' => $source->name, 'description' => $source->description]
        );
    }

    protected function keepSlug(string $type, array $item, ?object $keepSlug): void
    {
        if (! $keepSlug || DB::table('slugs_translations')->where(['lang_code' => $item['lang'], 'slugs_id' => $keepSlug->id])->exists()) {
            return;
        }
        // The merged row's own slug is deleted below, so its key becomes free.
        DB::table('slugs')->where(['reference_type' => $type, 'reference_id' => $item['id']])->delete();
        if ($item['slug'] && $item['slug'] !== $keepSlug->key
            && ! TranslatedSlug::taken($item['slug'], (string) $keepSlug->prefix, $keepSlug->id, $item['lang'])) {
            DB::table('slugs_translations')->insert([
                'lang_code' => $item['lang'], 'slugs_id' => $keepSlug->id, 'key' => $item['slug'], 'prefix' => (string) $keepSlug->prefix,
            ]);

            return;
        }
        // Same URL in every language (the seeded categories): one from the translated name.
        TranslatedSlug::save($type, $keepSlug->reference_id, $item['lang'], (string) $item['name']);
    }

    protected function deleteRow(string $type, int $id): void
    {
        $slugIds = DB::table('slugs')->where(['reference_type' => $type, 'reference_id' => $id])->pluck('id');
        DB::table('slugs_translations')->whereIn('slugs_id', $slugIds)->delete();
        DB::table('slugs')->whereIn('id', $slugIds)->delete();
        DB::table('language_meta')->where(['reference_type' => $type, 'reference_id' => $id])->delete();
        DB::table('meta_boxes')->where(['reference_type' => $type, 'reference_id' => $id])->delete();
        [$table, $fk] = $type === Category::class ? ['categories_translations', 'categories_id'] : ['tags_translations', 'tags_id'];
        DB::table($table)->where($fk, $id)->delete();
        DB::table((new $type())->getTable())->where('id', $id)->delete();
    }

    protected function pivot(string $type)
    {
        return DB::table($type === Category::class ? 'post_categories' : 'post_tags');
    }

    protected function pivotKey(string $type): string
    {
        return $type === Category::class ? 'category_id' : 'tag_id';
    }

    protected function defaultLocale(): string
    {
        return is_plugin_active('language') ? Language::getDefaultLocaleCode() : 'es';
    }
}
