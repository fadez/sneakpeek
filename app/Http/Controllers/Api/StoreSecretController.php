<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreSecretRequest;
use App\Http\Resources\SecretResource;
use App\Services\SecretService;

final readonly class StoreSecretController
{
    /**
     * Store a new secret and return it along with its access token,
     * which is visible only at creation and can never be retrieved again.
     */
    public function __invoke(StoreSecretRequest $request, SecretService $secretService): SecretResource
    {
        $createdSecret = $secretService->createSecret($request->validatedToDTO());

        return new SecretResource($createdSecret->secret)
            ->additional([
                'secret' => [
                    'access_token' => $createdSecret->accessToken,
                ],
            ]);
    }
}
