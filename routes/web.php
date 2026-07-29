<?php

use Database\Seeders\TornsContentSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| One-shot protected seeder route (for hostings without SSH access)
|--------------------------------------------------------------------------
|
| Runs TornsContentSeeder from the browser when no SSH is available:
|
|   1. Set INSTALL_SEED_TOKEN in .env to a long random string.
|   2. Visit /install-seed/{that-token}.
|   3. The route runs the seeder once and writes a lock file so it can
|      never run again. Remove INSTALL_SEED_TOKEN from .env afterwards.
|
| The route is only registered when the token is configured, and refuses
| to run once the lock file exists.
*/
if (env('INSTALL_SEED_TOKEN')) {
    Route::get('install-seed/{token}', function (string $token) {
        $configuredToken = (string) env('INSTALL_SEED_TOKEN');
        $lockFile = storage_path('installed-content-seed.lock');

        abort_unless(hash_equals($configuredToken, $token), 404);

        if (file_exists($lockFile)) {
            return response(
                'The content seeder has already been executed on ' . file_get_contents($lockFile)
                . '. Delete storage/installed-content-seed.lock to allow a re-run.',
                410
            );
        }

        Artisan::call('db:seed', ['--class' => TornsContentSeeder::class, '--force' => true]);

        file_put_contents($lockFile, now()->toDateTimeString());

        return response(
            'Content seeded successfully. Now remove INSTALL_SEED_TOKEN from your .env file.'
        );
    })->middleware('web');
}
