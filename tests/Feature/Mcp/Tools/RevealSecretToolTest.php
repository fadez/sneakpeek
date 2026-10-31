<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\RevealSecretTool;
use App\Models\Secret;
use Illuminate\Support\Str;

it('reveals a secret and wipes its content', function () {
    $content = Str::random(32);
    $accessToken = Str::random(Secret::TOKEN_LENGTH);

    $secret = Secret::factory()->createFresh(['access_token' => $accessToken, 'content' => $content]);

    AppServer::tool(RevealSecretTool::class, ['id' => $secret->getKey(), 'access_token' => $accessToken])
        ->assertOk()
        ->assertSee($content);

    $secret->refresh();

    expect($secret->content)->toBeNull();
});

it('does not reveal a secret with a wrong access token', function () {
    $content = Str::random(32);
    $accessToken = Str::random(Secret::TOKEN_LENGTH);

    $secret = Secret::factory()->createFresh(['access_token' => $accessToken, 'content' => $content]);

    AppServer::tool(RevealSecretTool::class, ['id' => $secret->getKey(), 'access_token' => 'incorrect token'])
        ->assertHasErrors()
        ->assertDontSee($content);

    expect($secret->refresh()->content)->toBe($content);
});
