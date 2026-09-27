<?php

declare(strict_types=1);

use App\Http\Resources\SecretResource;
use App\Models\Secret;

/**
 * @phpstan-import-type JsonApiResourceData from SecretResource
 */
test('resolve', function () {
    $secret = Secret::factory()->createFresh();

    /** @var JsonApiResourceData $data */
    $data = SecretResource::make($secret)->resolve();

    expect(array_keys(get_object_vars($data['data']['attributes'])))->toBe([
        'id',
        'created_at',
        'expires_at',
        'revealed_at',
        'is_available',
        'is_expired',
        'is_passphrase_protected',
        'is_revealed',
    ]);
});
