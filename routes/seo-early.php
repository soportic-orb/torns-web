<?php

use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

/*
| SEO and AI-readable URLs that Botble does not route itself (docs/seo-geo.md).
| Registered before Botble's catch-all page route, which would take them.
| The Markdown version of pages and posts: /funcionalidades.md, /ca/blog/x.md…
*/

Route::get('robots.txt', [SeoController::class, 'robots']);
Route::get('llms-full.txt', [SeoController::class, 'llmsFull']);

Route::middleware('web')->group(function (): void {
    Route::get('{first}/{second}.md', [SeoController::class, 'markdown'])
        ->where(['first' => '[a-z0-9-]+', 'second' => '[a-z0-9-]+(/[a-z0-9-]+)?']);
    Route::get('{first}.md', [SeoController::class, 'markdown'])
        ->where('first', '[a-z0-9-]+');
});

// URLs of the previous WordPress website (config/legacy-redirects.php).
foreach (config('legacy-redirects', []) as $from => $to) {
    Route::permanentRedirect($from, $to);
}
