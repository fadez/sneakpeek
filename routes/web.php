<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\WhoAmIController;
use Illuminate\Support\Facades\Route;

Route::get('/', AppController::class)->name('home');

Route::get('/who-is-alex-fadez', WhoAmIController::class)->name('who-is-alex-fadez');

Route::fallback(AppController::class);
