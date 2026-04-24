<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;

final readonly class AppController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        return view('app');
    }
}
