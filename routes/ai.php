<?php

declare(strict_types=1);

use App\Mcp\Servers\AppServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp', AppServer::class)
    ->middleware(['throttle:mcp']);
