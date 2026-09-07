<?php

declare(strict_types=1);

use App\Models\Secret;

dataset('routes_web_public', [
    'home' => [fn (): array => [
        'name' => 'home',
        'url' => route('home', [], false),
    ]],
]);

dataset('routes_api_not_cached', [
    'api.secrets.receipt' => [fn (): array => [
        'name' => 'api.secrets.receipt',
        'url' => route('api.secrets.receipt', ['secret' => Secret::factory()->createFresh()->getKey()], false),
    ]],
    'api.secrets.show' => [function (): array {
        $accessToken = 'secret_access_token';

        $secret = Secret::factory()->createFresh(['access_token' => $accessToken]);

        return [
            'url' => route('api.secrets.show', ['secret' => $secret->getKey()], false),
            'headers' => ['X-Access-Token' => $accessToken],
        ];
    }],
]);
