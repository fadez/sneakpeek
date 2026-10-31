<?php

declare(strict_types=1);

namespace App\Mcp\Servers;

use App\Mcp\Tools\BurnSecretTool;
use App\Mcp\Tools\CreateSecretTool;
use App\Mcp\Tools\GetSecretTool;
use App\Mcp\Tools\GetStatisticsTool;
use App\Mcp\Tools\RevealSecretTool;
use Illuminate\Support\Facades\Vite;
use Laravel\Mcp\Schema\Icon;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;
use Laravel\Mcp\Server\Prompt;
use Laravel\Mcp\Server\Tool;

#[Name('SneakPeek Server')]
#[Version('1.0.0')]
#[Instructions('This server provides tools for interacting with SneakPeek, a one-time secret-sharing application.')]
final class AppServer extends Server
{
    /**
     * The tools registered with this MCP server.
     *
     * @var array<int, class-string<Tool>>
     */
    protected array $tools = [
        // Secrets
        CreateSecretTool::class,
        GetSecretTool::class,
        RevealSecretTool::class,
        BurnSecretTool::class,
        // Statistics
        GetStatisticsTool::class,
    ];

    /**
     * The resources registered with this MCP server.
     *
     * @var array<int, class-string<Server\Resource>>
     */
    protected array $resources = [
        //
    ];

    /**
     * The prompts registered with this MCP server.
     *
     * @var array<int, class-string<Prompt>>
     */
    protected array $prompts = [
        //
    ];

    /**
     * Get the server's icons.
     *
     * @return array<int, Icon>
     */
    public function icons(): array
    {
        return [
            Icon::from(Vite::asset('resources/images/logo.svg'), mimeType: 'image/svg+xml'),
        ];
    }
}
