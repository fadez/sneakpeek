<?php

use App\Models\Secret;

dataset('routes_web_public', [
    'home' => [fn () => [
        'name' => 'home',
        'url' => route('home', [], false),
    ]],
]);

dataset('routes_api_not_cached', [
    'api.secrets.show' => [fn () => [
        'name' => 'api.secrets.show',
        'url' => route('api.secrets.show', ['secret' => Secret::factory()->createFresh()->getKey()], false),
    ]],
]);
