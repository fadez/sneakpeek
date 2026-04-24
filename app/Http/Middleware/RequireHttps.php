<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequireHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // We must enforce HTTPS to ensure privacy and security
        abort_if(! app()->environment('testing') && ! $request->isSecure(), 403, 'HTTPS is required to access this application.');

        return $next($request);
    }
}
