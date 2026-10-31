<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Services\StatisticService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_statistics')]
#[Description('Get a snapshot of current application usage statistics.')]
#[IsReadOnly]
final class GetStatisticsTool extends Tool
{
    /**
     * Create a new tool instance.
     */
    public function __construct(
        private readonly StatisticService $statisticService
    ) {
        //
    }

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        return Response::json([
            'statistics' => $this->statisticService->getSnapshot(),
        ]);
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            //
        ];
    }
}
