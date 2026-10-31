<?php

declare(strict_types=1);

namespace App\Mcp\Tools;

use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Validation\ValidationException;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('reveal_secret')]
#[Description('Reveal a secret and permanently wipe its content from the database.')]
#[IsDestructive]
final class RevealSecretTool extends Tool
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
        $request->validate([
            'id' => ['required', 'string', 'alpha_num'],
            'access_token' => ['required', 'string'],
            'passphrase' => ['nullable', 'string'],
        ]);

        try {
            $secret = Secret::query()->findOrFail($request->string('id')->value());

            $this->secretService->validateAccessToAvailableSecret(
                secret: $secret,
                accessToken: $request->string('access_token')->value(),
                passphrase: $request->filled('passphrase') ? $request->string('passphrase')->value() : null,
            );

            $content = $this->secretService->revealSecret($secret);
        } catch (ModelNotFoundException) {
            return Response::error('Secret not found, no longer available, or the access token is incorrect.');
        } catch (ValidationException) {
            return Response::error('The passphrase is incorrect.');
        }

        return Response::json(['content' => $content]);
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
            'access_token' => $schema->string()
                ->description('The access token required to reveal the secret.')
                ->required(),
            'passphrase' => $schema->string()
                ->description('The passphrase is required to reveal the secret if secret is passphrase-protected.'),
        ];
    }
}
