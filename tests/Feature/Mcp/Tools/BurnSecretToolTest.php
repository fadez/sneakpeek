<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\BurnSecretTool;
use App\Models\Secret;
use Illuminate\Support\Str;

it('burns a secret with the correct access token', function () {
    $accessToken = Str::random(Secret::TOKEN_LENGTH);

    $secret = Secret::factory()->createFresh(['access_token' => $accessToken]);

    AppServer::tool(BurnSecretTool::class, ['id' => $secret->getKey(), 'access_token' => $accessToken])
        ->assertOk();

    expect(Secret::query()->whereKey($secret->getKey())->exists())->toBeFalse();
});

it('requires the correct passphrase for a passphrase-protected secret', function () {
    $accessToken = Str::random(Secret::TOKEN_LENGTH);
    $passphrase = Str::random(16);

    $secret = Secret::factory()
        ->passphraseProtected(passphrase: $passphrase)
        ->createFresh(['access_token' => $accessToken]);

    AppServer::tool(BurnSecretTool::class, [
        'id' => $secret->getKey(),
        'access_token' => $accessToken,
        'passphrase' => 'incorrect passphrase',
    ])->assertHasErrors();

    expect(Secret::query()->whereKey($secret->getKey())->exists())->toBeTrue();

    AppServer::tool(BurnSecretTool::class, [
        'id' => $secret->getKey(),
        'access_token' => $accessToken,
        'passphrase' => $passphrase,
    ])->assertOk();

    expect(Secret::query()->whereKey($secret->getKey())->exists())->toBeFalse();
});

it('does not burn a secret with an incorrect access token', function () {
    $secret = Secret::factory()->createFresh(['access_token' => Str::random(Secret::TOKEN_LENGTH)]);

    AppServer::tool(BurnSecretTool::class, ['id' => $secret->getKey(), 'access_token' => 'incorrect token'])
        ->assertHasErrors();

    expect(Secret::query()->whereKey($secret->getKey())->exists())->toBeTrue();
});
