<?php

use App\Models\Secret;

dataset('routes_web_public', [
    'home' => [fn () => [
        'name' => 'home',
        'url' => route('home', [], false),
    ]],
]);

dataset('routes_api_not_cached', [
    'api.secrets.receipt' => [fn () => [
        'name' => 'api.secrets.receipt',
        'url' => route('api.secrets.receipt', ['secret' => Secret::factory()->createFresh()->getKey()], false),
    ]],
    'api.secrets.show' => [fn () => [
        'name' => 'api.secrets.show',
        'url' => route('api.secrets.show', ['accessToken' => Secret::factory()->createFresh()->access_token], false),
    ]],
]);
