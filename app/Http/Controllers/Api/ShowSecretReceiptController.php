<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\SecretResource;
use App\Models\Secret;

final readonly class ShowSecretReceiptController
{
    /**
     * Get information about a secret.
     */
    public function __invoke(Secret $secret): SecretResource
    {
        return new SecretResource($secret);
    }
}
