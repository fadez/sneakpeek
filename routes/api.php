<?php

declare(strict_types=1);

use App\Http\Controllers\Api\DeactivateFeatureController;
use App\Http\Controllers\Api\DestroySecretController;
use App\Http\Controllers\Api\IndexFeatureController;
use App\Http\Controllers\Api\RevealSecretController;
use App\Http\Controllers\Api\ShowSecretController;
use App\Http\Controllers\Api\ShowStatisticController;
use App\Http\Controllers\Api\StoreSecretController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware(['throttle:api'])->group(function () {
    // The "cache.headers:no_store" middleware is needed to prevent sensitive data from being cached in the browser's Back-Forward Cache (bfcache).
    // This ensures secrets aren't accessible when navigating via the browser's back or forward buttons and forces the app to always fetch fresh data from API.
    Route::name('secrets.')->prefix('secrets')->middleware('cache.headers:no_store')->group(function () {
        Route::post('/', StoreSecretController::class)
            ->middleware('throttle:10,1')
            ->name('store');

        Route::get('/{secret}', ShowSecretController::class)
            ->whereAlphaNumeric('secret')
            ->name('show');

        Route::post('/{secret}/reveal', RevealSecretController::class)
            ->whereAlphaNumeric('secret')
            ->middleware('throttle:10,1')
            ->name('reveal');

        Route::delete('/{secret}', DestroySecretController::class)
            ->whereAlphaNumeric('secret')
            ->name('destroy');
    });

    Route::name('features.')->prefix('features')->group(function () {
        Route::get('/', IndexFeatureController::class)
            ->name('index');

        Route::post('/{feature}/deactivate', DeactivateFeatureController::class)
            ->name('deactivate');
    });

    Route::name('statistics.')->prefix('statistics')->group(function () {
        Route::get('/', ShowStatisticController::class)
            ->name('show');
    });
});
