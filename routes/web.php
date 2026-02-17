<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
})->name('home');

Route::get('/receipts/{secret:key}', function () {
    return view('app');
})->whereAlphaNumeric('secret')->name('secrets.receipt');

Route::get('/secrets/{secretKey}', function () {
    return view('app');
})->whereAlphaNumeric('secret_key')->name('secrets.show');

Route::get('/who-is-alex-fadez', function () {
    abort(418);
})->name('who-is-alex-fadez');
