<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use App\Mcp\Tools\GetStatisticsTool;

it('returns the statistics snapshot', function () {
    AppServer::tool(GetStatisticsTool::class)
        ->assertOk()
        ->assertSee('secrets_created')
        ->assertSee('secrets_revealed')
        ->assertSee('secrets_expired')
        ->assertSee('secrets_burned');
});
