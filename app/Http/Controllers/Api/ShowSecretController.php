<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\ShowSecretRequest;
use App\Http\Resources\SecretResource;
use App\Models\Secret;
use App\Services\SecretService;

final readonly class ShowSecretController
{
    /**
     * Get information about a secret before revealing it.
     */
    public function __invoke(ShowSecretRequest $request, Secret $secret, SecretService $secretService): SecretResource
    {
        $secretService->validateAccessToken(
            secret: $secret,
            accessToken: $request->string('access_token')->value(),
        );

        $secretService->validateAvailability(secret: $secret);

        return new SecretResource($secret);
    }
}
