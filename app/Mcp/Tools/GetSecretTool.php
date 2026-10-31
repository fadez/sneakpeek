<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Http\Resources\SecretResource;
use App\Models\Secret;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get_secret')]
#[Description('Get the status of a secret. Returns secret metadata only, such as whether it is still available, has been revealed, or has expired.')]
#[IsReadOnly]
final class GetSecretTool extends Tool
{
    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $request->validate([
            'id' => ['required', 'string', 'alpha_num'],
        ]);

        $secret = Secret::query()->find($request->string('id')->value());

        if ($secret === null) {
            return Response::error('Secret not found.');
        }

        return Response::json(new SecretResource($secret)->resolve());
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->string()
                ->description('The ID of the secret.')
                ->required(),
        ];
    }
}
