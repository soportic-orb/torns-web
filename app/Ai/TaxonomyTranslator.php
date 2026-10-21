<?php

namespace App\Ai;

use App\Support\TranslatedSlug;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

/**
 * Translates blog categories and tags with BlogAi, stored the way
 * language-advanced reads them: {table}_translations (name, description),
 * slugs_translations (the URL) and "<lang>_seo_meta" when the Spanish one
 * has SEO texts.
 *
 * "Missing" means no row, or a row that is still a copy of the Spanish:
 * Botble's AddDefaultTranslations copies the default values into every
 * language when a category or tag is created. A row that differs from the
 * Spanish was written by someone and is only replaced with $overwrite.
 */
class TaxonomyTranslator
{
    public function __construct(protected BlogAi $ai, protected PostTranslator $posts)
    {
    }

    /** @return array{table: string, fk: string, type: class-string} */
    protected function meta(Category|Tag $model): array
    {
        return $model instanceof Category
            ? ['table' => 'categories_translations', 'fk' => 'categories_id', 'type' => Category::class]
            : ['table' => 'tags_translations', 'fk' => 'tags_id', 'type' => Tag::class];
    }

    public function languages(): array
    {
        return $this->posts->languages();
    }

    /** The Spanish values, straight from the table (the model may carry a translation). */
    protected function source(Category|Tag $model): object
    {
        return DB::table($model->getTable())->where('id', $model->getKey())->first(['name', 'description']) ?? (object) [];
    }

    /**
     * @return list<string> languages still without their own translation:
     *                      no row, an empty name, or the Spanish copy Botble makes on create
     *                      (unless the AI already translated it and the word is the same, as
     *                      «Funcionalidades» in Galician).
     */
    public function missing(Category|Tag $model): array
    {
        ['table' => $table, 'fk' => $fk, 'type' => $type] = $this->meta($model);
        $source = $this->source($model);
        $rows = DB::table($table)->where($fk, $model->getKey())->get()->keyBy('lang_code');
        $confirmed = $this->confirmed($type, $model->getKey());

        return array_values(array_filter($this->languages(), function (string $language) use ($rows, $source, $confirmed) {
            $row = $rows->get($language);
            if (! $row || trim((string) $row->name) === '') {
                return true;
            }

            return trim((string) $row->name) === trim((string) ($source->name ?? '')) && ! in_array($language, $confirmed, true);
        }));
    }

    /** Languages the AI translated (meta box torns_ai_translated). */
    protected function confirmed(string $type, int|string $id): array
    {
        $value = DB::table('meta_boxes')->where(['meta_key' => 'torns_ai_translated', 'reference_type' => $type, 'reference_id' => $id])->value('meta_value');
        $value = json_decode((string) $value, true);

        return is_array($value) ? array_values(array_filter((array) ($value[0] ?? []), 'is_string')) : [];
    }

    protected function confirm(string $type, int|string $id, string $language): void
    {
        $languages = array_values(array_unique([...$this->confirmed($type, $id), $language]));
        $this->saveSeoMeta($type, $id, 'torns_ai_translated', $languages);
    }

    /**
     * Translated names that still have no translated URL (written by hand,
     * or merged from the old one-row-per-language categories) get one from
     * the name, without the AI. Returns the languages fixed.
     *
     * @return list<string>
     */
    public function syncSlugs(Category|Tag $model): array
    {
        ['table' => $table, 'fk' => $fk, 'type' => $type] = $this->meta($model);
        $slugId = DB::table('slugs')->where(['reference_type' => $type, 'reference_id' => $model->getKey()])->value('id');
        if (! $slugId) {
            return [];
        }
        $missing = $this->missing($model);
        $done = DB::table('slugs_translations')->where('slugs_id', $slugId)->pluck('lang_code')->all();
        $fixed = [];
        foreach (DB::table($table)->where($fk, $model->getKey())->get() as $row) {
            if (in_array($row->lang_code, $this->languages(), true) && ! in_array($row->lang_code, $missing, true) && ! in_array($row->lang_code, $done, true)) {
                TranslatedSlug::save($type, $model->getKey(), $row->lang_code, (string) $row->name);
                $fixed[] = $row->lang_code;
            }
        }

        return $fixed;
    }

    /** @return array{language: string, status: 'saved'|'skipped'} */
    public function translate(Category|Tag $model, string $language, string $provider, bool $overwrite = false, ?int $userId = null): array
    {
        if (! in_array($language, $this->languages(), true)) {
            throw new RuntimeException(trans('torns.ai_err_translate_language'));
        }
        if (! $overwrite && ! in_array($language, $this->missing($model), true)) {
            $this->syncSlugs($model);

            return ['language' => $language, 'status' => 'skipped'];
        }

        ['table' => $table, 'fk' => $fk, 'type' => $type] = $this->meta($model);
        $source = $this->source($model);
        $seo = $this->seoMeta($type, $model->getKey(), 'seo_meta');
        $result = $this->ai->translate($provider, [
            'name' => $source->name ?? '',
            'description' => $source->description ?? '',
            'seo_title' => $seo['seo_title'] ?? '',
            'seo_description' => $seo['seo_description'] ?? '',
        ], $language, $userId, $model instanceof Category ? 'category' : 'tag');

        DB::transaction(function () use ($table, $fk, $type, $model, $language, $result, $seo): void {
            DB::table($table)->updateOrInsert(
                ['lang_code' => $language, $fk => $model->getKey()],
                ['name' => $result['name'], 'description' => $result['description'] ?: null]
            );
            if ($result['seo_title'] !== '' || $result['seo_description'] !== '') {
                $this->saveSeoMeta($type, $model->getKey(), "{$language}_seo_meta", [
                    'seo_title' => $result['seo_title'],
                    'seo_description' => $result['seo_description'],
                ] + array_intersect_key($seo, ['index' => true]));
            }
            TranslatedSlug::save($type, $model->getKey(), $language, $result['name']);
            $this->confirm($type, $model->getKey(), $language);
        });

        return ['language' => $language, 'status' => 'saved'];
    }

    /**
     * Fills every missing translation, quietly: used after a save in the
     * admin, where nobody waits for it. Returns the languages saved.
     *
     * @return list<string>
     */
    public function translateMissing(Category|Tag $model, ?string $provider = null, ?array $only = null): array
    {
        $this->syncSlugs($model);
        $provider = $provider ?: $this->ai->defaultTextProvider();
        if (! $provider) {
            return [];
        }
        $saved = [];
        foreach ($this->missing($model) as $language) {
            if ($only !== null && ! in_array($language, $only, true)) {
                continue;
            }
            try {
                if ($this->translate($model, $language, $provider)['status'] === 'saved') {
                    $saved[] = $language;
                }
            } catch (Throwable $e) {
                Log::warning('Torns blog AI: taxonomy translation failed', [
                    'type' => class_basename($model), 'id' => $model->getKey(), 'language' => $language, 'error' => $e->getMessage(),
                ]);
            }
        }

        return $saved;
    }

    /** The post's categories and tags still without a translation. */
    public function translatePostTaxonomy(Post $post, ?string $provider = null, ?array $only = null): void
    {
        $post->loadMissing(['categories', 'tags']);
        foreach ([...$post->categories, ...$post->tags] as $model) {
            $this->translateMissing($model, $provider, $only);
        }
    }

    protected function seoMeta(string $type, int|string $id, string $key): array
    {
        $value = DB::table('meta_boxes')->where(['meta_key' => $key, 'reference_id' => $id, 'reference_type' => $type])->value('meta_value');
        $value = json_decode((string) $value, true);

        return is_array($value) ? (array) ($value[0] ?? []) : [];
    }

    protected function saveSeoMeta(string $type, int|string $id, string $key, array $value): void
    {
        $where = ['meta_key' => $key, 'reference_id' => $id, 'reference_type' => $type];
        $data = ['meta_value' => json_encode([$value]), 'updated_at' => now()];

        DB::table('meta_boxes')->where($where)->exists()
            ? DB::table('meta_boxes')->where($where)->update($data)
            : DB::table('meta_boxes')->insert($where + $data + ['created_at' => now()]);
    }
}
