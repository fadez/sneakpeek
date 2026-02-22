<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app')->name('home');

Route::get('/who-is-alex-fadez', fn () => abort(418))->name('who-is-alex-fadez');

Route::fallback(fn () => view('app'));
