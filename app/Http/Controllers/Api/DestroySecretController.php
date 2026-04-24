<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\DestroySecretRequest;
use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Http\Response;

final readonly class DestroySecretController
{
    /**
     * Delete a secret from the database permanently.
     */
    public function __invoke(DestroySecretRequest $request, Secret $secret, SecretService $secretService): Response
    {
        $secretService->validateAccessToAvailableSecret(
            secret: $secret,
            accessToken: $request->string('access_token')->value(),
            passphrase: $request->filled('passphrase') ? $request->string('passphrase')->value() : null,
        );

        $secretService->burnSecret($secret);

        return response()->noContent();
    }
}
