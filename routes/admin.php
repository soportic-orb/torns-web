<?php

use App\Http\Controllers\Admin\BlogAiController;
use App\Http\Controllers\Admin\OtaController;
use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

/*
| Admin routes of the Torns site itself (loaded by AppServiceProvider).
*/

AdminHelper::registerRoutes(function (): void {
    Route::group(['prefix' => 'system/site-updates', 'as' => 'torns-ota.'], function (): void {
        Route::get('', [OtaController::class, 'index'])->name('index');
        Route::post('check', [OtaController::class, 'check'])->name('check');
        Route::post('apply', [OtaController::class, 'apply'])->name('apply');
        Route::post('rollback', [OtaController::class, 'rollback'])->name('rollback');
        Route::post('use-repository-content', [OtaController::class, 'useRepositoryContent'])->name('use-repository-content');
    });

    Route::group(['prefix' => 'blog/ai', 'as' => 'torns-ai.'], function (): void {
        Route::post('text', [BlogAiController::class, 'text'])->name('text')->middleware('throttle:20,1');
        Route::post('image', [BlogAiController::class, 'image'])->name('image')->middleware('throttle:10,1');
        Route::post('seo', [BlogAiController::class, 'seo'])->name('seo')->middleware('throttle:20,1');
        Route::post('translate', [BlogAiController::class, 'translate'])->name('translate')->middleware('throttle:20,1');
        Route::get('settings', [BlogAiController::class, 'settings'])->name('settings');
        Route::post('settings', [BlogAiController::class, 'saveSettings'])->name('settings.save');
        Route::post('settings/test', [BlogAiController::class, 'test'])->name('settings.test');
    });
});
