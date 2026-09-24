<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Secret;

final readonly class CreateSecretResult
{
    /**
     * Create a new DTO instance.
     */
    public function __construct(
        public Secret $secret,
        public string $accessToken,
    ) {
        //
    }
}
