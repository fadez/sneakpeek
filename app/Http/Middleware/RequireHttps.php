<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // We must enforce HTTPS on production environment to ensure privacy and security
        if (app()->environment('production') && ! $request->isSecure()) {
            abort(403, 'HTTPS is required to access this application.');
        }

        return $next($request);
    }
}
