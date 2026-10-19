<?php

use App\Ota\OtaUpdater;
use App\Support\SiteContent;
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

// The update is applied by hand from the panel; the check only tells it there is one.
Schedule::command('torns:ota:check')->hourly()->withoutOverlapping();
