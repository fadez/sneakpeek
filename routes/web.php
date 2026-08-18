<?php

declare(strict_types=1);

use App\Http\Controllers\AppController;
use App\Http\Controllers\SiteManifestController;
use App\Http\Controllers\WhoIsAleksFadezController;
use Illuminate\Support\Facades\Route;

Route::get('/', AppController::class)
    ->name('home');

Route::get('/favicon/site.webmanifest', SiteManifestController::class)
    ->name('favicon.manifest');

Route::get('/who-is-aleks-fadez', WhoIsAleksFadezController::class)
    ->name('who-is-aleks-fadez');

Route::fallback(AppController::class);
