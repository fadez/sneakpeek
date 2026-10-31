<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\SecretResource;
use App\Models\Secret;

final readonly class ShowSecretReceiptController
{
    /**
     * Get a secret's status for the receipt page.
     *
     * No access token is needed, since the id is unguessable and the response never includes the secret's content.
     */
    public function __invoke(Secret $secret): SecretResource
    {
        return new SecretResource($secret);
    }
}
