<?php

use App\Http\Resources\SecretResource;
use App\Models\Secret;

test('resolve', function () {
    $secret = Secret::factory()->createFresh();

    $data = SecretResource::make($secret)->resolve();

    expect(array_keys($data))->toBe([
        'id',
        'created_at',
        'expires_at',
        'revealed_at',
        'is_passphrase_protected',
        'is_expired',
        'is_revealed',
        'is_available',
    ]);
});
