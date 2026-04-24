<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class CreateSecretData
{
    public function __construct(
        public string $content,
        public ?string $passphrase,
        public int $ttl,
    ) {
        //
    }
}
