<?php

namespace App\Http\Controllers;

class WhoAmIController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): void
    {
        abort(418);
    }
}
