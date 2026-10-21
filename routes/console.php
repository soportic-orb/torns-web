<?php

use App\Ai\BlogAi;
use App\Ai\PostTranslator;
use App\Ai\TaxonomyTranslator;
use App\Ota\OtaUpdater;
use App\Support\IndexNow;
use App\Support\SiteContent;
use App\Support\TaxonomyMerge;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
use Botble\Blog\Models\Tag;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| OTA updates (docs/OTA.md)
|--------------------------------------------------------------------------
*/

Artisan::command('torns:ota:check', function (OtaUpdater $ota) {
    $available = $ota->check();
    $this->info($available
        ? "New version available: {$available['version']} (installed: " . ($ota->currentVersion() ?: 'none') . ')'
        : 'Up to date (' . ($ota->currentVersion() ?: 'no OTA version installed') . ').');
})->purpose('Look for a newer OTA release of the theme and site content');

Artisan::command('torns:ota:apply {--yes : Do not ask for confirmation}', function (OtaUpdater $ota) {
    $available = $ota->available() ?? $ota->check();
    if (! $available) {
        $this->info('Up to date.');

        return 0;
    }
    if (! $this->option('yes') && ! $this->confirm("Apply version {$available['version']}?")) {
        return 1;
    }
    $result = $ota->apply('console');
    $this->info("Version {$result['version']} applied.");
    foreach ($result['report']['conflicts'] as $item) {
        $this->warn("Kept the /admin edit of {$item} (the update did not overwrite it).");
    }

    return 0;
})->purpose('Download, verify and apply the newest OTA release');

Artisan::command('torns:ota:rollback {--yes : Do not ask for confirmation}', function (OtaUpdater $ota) {
    if (! $this->option('yes') && ! $this->confirm('Go back to the files and pages saved before the last update?')) {
        return 1;
    }
    $version = $ota->rollback('console');
    $this->info('Restored ' . ($version ?: 'the pre-OTA installation') . '.');

    return 0;
})->purpose('Restore the backup taken before the last OTA update');

Artisan::command('torns:content:sync {--force : Overwrite pages and menus edited in /admin}', function (SiteContent $content) {
    $report = $content->sync((bool) $this->option('force'));
    $this->info(sprintf('%d created, %d updated, %d unchanged.', count($report['created']), count($report['updated']), count($report['unchanged'])));
    foreach ($report['conflicts'] as $item) {
        $this->warn("Kept the /admin edit of {$item}.");
    }
})->purpose('Write content/site pages and menus into the database');

/*
|--------------------------------------------------------------------------
| Blog translation with AI (docs/blog-ai.md)
|--------------------------------------------------------------------------
*/

Artisan::command('torns:blog:translate {post?* : Post ids (all posts if empty)} {--lang=* : Target languages (all if empty)} {--provider= : claude or openai} {--overwrite : Replace existing translations}', function (BlogAi $ai, PostTranslator $translator) {
    $provider = $this->option('provider') ?: $ai->defaultTextProvider();
    if (! $provider || ! isset($ai->textProviders()[$provider])) {
        $this->error('No AI text service is enabled and configured (Admin → Settings → AI for the blog).');

        return 1;
    }
    $languages = $this->option('lang') ?: $translator->languages();
    $posts = Post::query()->when($this->argument('post'), fn ($query, $ids) => $query->whereIn('id', $ids))->orderBy('id')->get();

    $failed = 0;
    foreach ($posts as $post) {
        foreach ($languages as $language) {
            try {
                $result = $translator->translate($post, $language, $provider, (bool) $this->option('overwrite'));
                $this->line("#{$post->getKey()} {$language}: {$result['status']}");
            } catch (Throwable $e) {
                $failed++;
                $this->error("#{$post->getKey()} {$language}: {$e->getMessage()}");
            }
        }
    }

    return $failed ? 1 : 0;
})->purpose('Translate blog posts from Spanish with AI (skips existing translations unless --overwrite)');

Artisan::command('torns:blog:taxonomy-merge {--apply : Merge (without it, only shows what would change)}', function (TaxonomyMerge $merge) {
    $plan = $merge->plan();
    if (! $plan) {
        $this->info('Nothing to merge: every category and tag is a single row with its translations.');

        return 0;
    }
    foreach ($plan as $group) {
        $type = class_basename($group['type']);
        $this->line("{$type} #{$group['keep']['id']} «{$group['keep']['name']}» ({$group['keep']['lang']}) keeps:");
        foreach ($group['merge'] as $item) {
            $this->line("  ← #{$item['id']} «{$item['name']}» ({$item['lang']}), URL {$item['slug']}, {$item['posts']} post(s) moved");
        }
    }
    if (! $this->option('apply')) {
        $this->warn('Dry run: nothing changed. Run again with --apply to merge (back up the database first).');

        return 0;
    }
    $stats = $merge->apply();
    $this->info("Merged {$stats['rows']} row(s) in {$stats['groups']} group(s); {$stats['posts_moved']} post link(s) moved.");

    return 0;
})->purpose('Merge the blog categories/tags written as one row per language into one row with translations');

Artisan::command('torns:blog:translate-taxonomy {--type=* : category and/or tag (both if empty)} {--lang=* : Target languages (all if empty)} {--provider= : claude or openai} {--overwrite : Replace existing translations}', function (BlogAi $ai, TaxonomyTranslator $translator) {
    $provider = $this->option('provider') ?: $ai->defaultTextProvider();
    if (! $provider || ! isset($ai->textProviders()[$provider])) {
        $this->error('No AI text service is enabled and configured (Admin → Settings → AI for the blog).');

        return 1;
    }
    $types = $this->option('type') ?: ['category', 'tag'];
    $languages = $this->option('lang') ?: $translator->languages();
    $failed = 0;
    foreach ($types as $type) {
        $models = ($type === 'tag' ? Tag::query() : Category::query())->orderBy('id')->get();
        foreach ($models as $model) {
            foreach ($languages as $language) {
                try {
                    $result = $translator->translate($model, $language, $provider, (bool) $this->option('overwrite'));
                    $this->line("{$type} #{$model->getKey()} «{$model->getRawOriginal('name')}» {$language}: {$result['status']}");
                } catch (Throwable $e) {
                    $failed++;
                    $this->error("{$type} #{$model->getKey()} {$language}: {$e->getMessage()}");
                }
            }
        }
    }

    return $failed ? 1 : 0;
})->purpose('Translate blog categories and tags from Spanish with AI (skips translations written by hand unless --overwrite)');

Artisan::command('torns:indexnow {url?* : Absolute URLs (all the sitemap URLs if empty)}', function (IndexNow $indexNow) {
    if (! $indexNow->enabled()) {
        $this->warn('IndexNow is off: it only runs with APP_ENV=production and INDEXNOW_ENABLED not false.');

        return 1;
    }
    $urls = $this->argument('url') ?: $indexNow->allUrls();
    $result = $indexNow->submit($urls);
    $this->line(match ($result) {
        true => 'Sent ' . count($urls) . ' URLs to IndexNow.',
        false => 'IndexNow did not accept the request (see storage/logs).',
        default => 'Nothing to send.',
    });

    return $result === false ? 1 : 0;
})->purpose('Tell search engines (IndexNow: Bing, Yandex…) about new or changed URLs');

// The update is applied by hand from the panel; the check only tells it there is one.
Schedule::command('torns:ota:check')->hourly()->withoutOverlapping();
