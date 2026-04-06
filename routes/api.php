<?php

declare(strict_types=1);

use App\Http\Controllers\Api\FeatureController;
use App\Http\Controllers\Api\SecretController;
use App\Http\Controllers\Api\StatisticController;
use Illuminate\Support\Facades\Route;

// The "cache.headers:no_store" middleware is needed to prevent sensitive data from being cached in the browser's Back-Forward Cache (bfcache).
// This ensures secrets aren't accessible when navigating via the browser's back or forward buttons and forces the app to always fetch fresh data from API.
Route::name('api.')->middleware(['throttle:api', 'cache.headers:no_store'])->group(function () {
    Route::get('/statistics', StatisticController::class);

    Route::name('features.')->prefix('features')->group(function () {
        Route::get('/', [FeatureController::class, 'index'])
            ->name('index');

        Route::post('/{feature}/deactivate', [FeatureController::class, 'deactivate'])
            ->name('deactivate');
    });

    Route::name('secrets.')->prefix('secrets')->group(function () {
        Route::post('/', [SecretController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('store');

        Route::get('/{secret}', [SecretController::class, 'show'])
            ->whereAlphaNumeric('secret')
            ->name('show');

        Route::post('/{secret}/reveal', [SecretController::class, 'reveal'])
            ->whereAlphaNumeric('secret')
            ->middleware('throttle:10,1')
            ->name('reveal');

        Route::delete('/{secret}', [SecretController::class, 'destroy'])
            ->whereAlphaNumeric('secret')
            ->name('destroy');
    });
});
