<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\GetSecretTool;
use App\Models\Secret;
use Illuminate\Support\Str;

it('returns the status of a secret without its content', function () {
    $content = Str::random(32);

    $secret = Secret::factory()->createFresh(['content' => $content]);

    AppServer::tool(GetSecretTool::class, ['id' => $secret->getKey()])
        ->assertOk()
        ->assertSee($secret->getKey())
        ->assertDontSee($content);
});

it('returns an error for an unknown secret', function () {
    AppServer::tool(GetSecretTool::class, ['id' => Str::random(Secret::TOKEN_LENGTH)])
        ->assertHasErrors();
});
