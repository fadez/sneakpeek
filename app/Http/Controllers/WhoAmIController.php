<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final readonly class WhoAmIController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): void
    {
        abort(418);
    }
}
