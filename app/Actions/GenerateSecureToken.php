<?php

declare(strict_types=1);

namespace App\Actions;

use Illuminate\Support\Str;
use InvalidArgumentException;

final readonly class GenerateSecureToken
{
    /**
     * Generate a cryptographically secure, random token suitable for use in URLs.
     */
    public function handle(int $length): string
    {
        throw_if($length <= 0, InvalidArgumentException::class, 'Token length must be greater than zero.');

        return Str::random($length);
    }
}
