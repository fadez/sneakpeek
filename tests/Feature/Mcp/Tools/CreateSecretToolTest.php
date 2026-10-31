<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\CreateSecretTool;
use App\Models\Secret;
use Illuminate\Support\Str;

it('creates a secret and returns a one-time link', function () {
    $content = Str::random(32);

    AppServer::tool(CreateSecretTool::class, ['content' => $content, 'ttl' => 3600])
        ->assertOk()
        ->assertSee('secret_url')
        ->assertDontSee($content);

    $secret = Secret::query()->sole();

    expect($secret->content)->toBe($content);
    expect($secret->is_available)->toBeTrue();
});
