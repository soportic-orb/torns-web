<?php

namespace App\Support;

use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * The URL of a translated post, category or tag (language-advanced:
 * slugs_translations), unique within its prefix across every language.
 * Posts and categories share the "blog" prefix, so a category and a post
 * can never end up with the same address.
 */
class TranslatedSlug
{
    /** Saves the translated key from the translated title; returns it, or null when the model has no slug. */
    public static function save(string $referenceType, int|string $referenceId, string $language, string $title): ?string
    {
        $slug = DB::table('slugs')->where(['reference_type' => $referenceType, 'reference_id' => $referenceId])->first(['id', 'key', 'prefix']);
        if (! $slug) {
            return null;
        }
        $prefix = (string) ($slug->prefix ?? SlugHelper::getPrefix($referenceType));
        $base = Str::slug($title) ?: $slug->key;
        if ($base === $slug->key) {
            // Spelled the same as in the default language: its own URL serves.
            DB::table('slugs_translations')->where(['lang_code' => $language, 'slugs_id' => $slug->id])->delete();

            return $slug->key;
        }

        $key = $base;
        for ($i = 2; self::taken($key, $prefix, $slug->id, $language); $i++) {
            $key = "{$base}-{$i}";
        }

        DB::table('slugs_translations')->updateOrInsert(
            ['lang_code' => $language, 'slugs_id' => $slug->id],
            ['key' => $key, 'prefix' => $prefix]
        );

        return $key;
    }

    public static function taken(string $key, string $prefix, int|string $slugId, string $language): bool
    {
        return DB::table('slugs')->where(['key' => $key, 'prefix' => $prefix])->where('id', '!=', $slugId)->exists()
            || DB::table('slugs_translations')->where(['key' => $key, 'prefix' => $prefix])
                ->where(fn ($query) => $query->where('slugs_id', '!=', $slugId)->orWhere('lang_code', '!=', $language))
                ->exists();
    }
}
