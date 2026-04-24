<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\RevealSecretRequest;
use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Http\JsonResponse;

final readonly class RevealSecretController
{
    /**
     * Reveal a secret and wipe its content.
     */
    public function __invoke(RevealSecretRequest $request, Secret $secret, SecretService $secretService): JsonResponse
    {
        $secretService->validateAccessToAvailableSecret(
            secret: $secret,
            accessToken: $request->string('access_token')->value(),
            passphrase: $request->filled('passphrase') ? $request->string('passphrase')->value() : null,
        );

        $content = $secretService->revealSecret($secret);

        return response()->json(['content' => $content]);
    }
}
