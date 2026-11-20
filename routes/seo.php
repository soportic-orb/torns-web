<?php

use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

/*
| SEO and AI-readable endpoints that Botble also defines (docs/seo-geo.md).
| Registered after Botble's routes (AppServiceProvider), so these replace its
| sitemap and llms.txt for the same URLs.
*/

Route::get('sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('llms.txt', [SeoController::class, 'llms']);

foreach (['ca', 'gl', 'eu'] as $locale) {
    Route::get("{$locale}/sitemap.xml", [SeoController::class, 'localeSitemap']);
}
