<?php

use App\Http\Controllers\Api\SecretController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->middleware('throttle:api')->group(function () {
    Route::get('/receipts/{secret:key}', [SecretController::class, 'receipt'])
        ->whereAlphaNumeric('secret')
        ->name('secrets.receipt');

    Route::get('/secrets/{secretKey}', [SecretController::class, 'show'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.show');

    Route::post('/secrets/{secretKey}/reveal', [SecretController::class, 'reveal'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.reveal')
        ->middleware('throttle:10,1');

    Route::delete('/secrets/{secretKey}', [SecretController::class, 'destroy'])
        ->whereAlphaNumeric('secret_key')
        ->name('secrets.destroy')
        ->middleware('throttle:10,1');

    Route::post('/secrets', [SecretController::class, 'store'])
        ->name('secrets.store')
        ->middleware('throttle:10,1');
});
