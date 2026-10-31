<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\DTOs\CreateSecretData;
use App\Http\Requests\StoreSecretRequest;
use App\Http\Resources\SecretResource;
use App\Services\SecretService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create_secret')]
#[Description('Create a one-time, expiring link for sharing a password, API key or other secret content. The secret content can be revealed only once. Only set a passphrase if the user explicitly provided one.')]
final class CreateSecretTool extends Tool
{
    /**
     * Create a new tool instance.
     */
    public function __construct(
        private readonly SecretService $secretService
    ) {
        //
    }

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $request->validate(new StoreSecretRequest()->rules());

        $result = $this->secretService->createSecret(new CreateSecretData(
            content: $request->string('content')->value(),
            passphrase: $request->filled('passphrase') ? $request->string('passphrase')->value() : null,
            ttl: $request->integer('ttl'),
        ));

        return Response::json(
            (new SecretResource($result->secret)->withAccessToken($result->accessToken)->resolve())
        );
    }

    /**
     * Get the tool's input schema.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'content' => $schema->string()
                ->description('The secret content to share (max 10000 characters).')
                ->required(),
            'ttl' => $schema->integer()
                ->description('Seconds until the link expires (60 to 7776000)')
                ->required(),
            'passphrase' => $schema->string()
                ->description('Optional passphrase the recipient must enter to reveal the secret (max 255 characters).'),
        ];
    }
}
