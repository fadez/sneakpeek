<?php

use App\Http\Controllers\Api\SecretController;
use Illuminate\Support\Facades\Route;

// The "cache.headers:no_store" middleware is needed to prevent sensitive data from being cached in the browser's Back-Forward Cache (bfcache).
// This ensures secrets aren't accessible when navigating via the browser's back or forward buttons and forces the app to always fetch fresh data from API.
Route::name('api.')->middleware(['throttle:api', 'cache.headers:no_store'])->group(function () {
    Route::name('secrets.')->group(function () {
        Route::post('/secrets', [SecretController::class, 'store'])
            ->name('store')
            ->middleware('throttle:10,1');

        Route::get('/secrets/{secret}', [SecretController::class, 'show'])
            ->whereAlphaNumeric(['secret'])
            ->name('show');

        Route::post('/secrets/{secret}/reveal', [SecretController::class, 'reveal'])
            ->whereAlphaNumeric(['secret'])
            ->name('reveal')
            ->middleware('throttle:10,1');

        Route::delete('/secrets/{secret}', [SecretController::class, 'destroy'])
            ->whereAlphaNumeric(['secret'])
            ->name('destroy');
    });
});
