<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\ShowSecretRequest;
use App\Http\Resources\SecretResource;
use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class ShowSecretController
{
    /**
     * Get a secret's status for the secret reveal page.
     *
     * The access token is required, so an attacker without it can't tell whether a secret exists.
     *
     * @throws ModelNotFoundException
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
