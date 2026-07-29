<?php

use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

// Design system preview — available only outside production for visual QA.
if (! app()->isProduction()) {
    Route::group(['middleware' => ['web', 'core']], function (): void {
        Route::get('design-system', function () {
            return Theme::scope('design-system', [], 'theme.torns::views.design-system')->render();
        })->name('theme.design-system');
    });
}

Theme::routes();
