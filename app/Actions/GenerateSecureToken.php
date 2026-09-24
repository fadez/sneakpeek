<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class GenerateSecureToken
{
    /**
     * Generate a cryptographically secure, random alphanumeric token (a-z, A-Z, 0-9).
     *
     * @throws InvalidArgumentException
     */
    public function handle(int $length): string
    {
        throw_if($length <= 0, InvalidArgumentException::class, 'Token length must be greater than zero.');

        return Str::random($length);
    }
}
