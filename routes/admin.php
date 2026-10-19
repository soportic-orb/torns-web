<?php

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
});
