<?php

use Illuminate\Support\Facades\Route;

Route::get('/who-is-alex-fadez', function () {
    abort(418);
});

Route::fallback(function () {
    return view('app');
})->name('home');
