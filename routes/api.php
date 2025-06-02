<?php

use App\Http\Controllers\Api\SecretController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('throttle:api')->group(function () {
    Route::get('/receipt/{secret:key}', [SecretController::class, 'receipt'])
        ->whereAlphaNumeric('secret')
        ->name('secrets.receipt');

    Route::get('/secret/{secretKey}', [SecretController::class, 'show'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.show');

    Route::post('/secret/{secretKey}', [SecretController::class, 'reveal'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.reveal')
        ->middleware('throttle:6,1');

    Route::delete('/secret/{secretKey}', [SecretController::class, 'destroy'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.destroy')
        ->middleware('throttle:12,1');

    Route::post('/secrets', [SecretController::class, 'store'])
        ->name('secrets.store')
        ->middleware('throttle:12,1');
});
