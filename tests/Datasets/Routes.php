<?php

use App\Models\Secret;

dataset('routes_web_public', [
    'home' => [fn () => [
        'name' => 'home',
        'url' => route('home', [], false),
    ]],
    'secrets.receipt' => [fn () => [
        'name' => 'secrets.receipt',
        'url' => route('secrets.receipt', ['secret' => Secret::factory()->create()->key], false),
    ]],
    'secrets.show' => [fn () => [
        'name' => 'secrets.show',
        'url' => route('secrets.show', ['secretKey' => Secret::factory()->create()->secret_key], false),
    ]],
]);

dataset('routes_api_not_cached', [
    'api.secrets.receipt' => [fn () => [
        'name' => 'api.secrets.receipt',
        'url' => route('api.secrets.receipt', ['secret' => Secret::factory()->createFresh()->key], false),
    ]],
    'api.secrets.show' => [fn () => [
        'name' => 'api.secrets.show',
        'url' => route('api.secrets.show', ['secretKey' => Secret::factory()->createFresh()->secret_key], false),
    ]],
]);
