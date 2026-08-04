<?php

declare(strict_types=1);

use App\Http\Middleware\RequireHttps;
use App\Http\Middleware\ThrottleRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // Default health check and broadcasting routes are disabled to prevent framework identification
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies (e.g. Cloudflare) as proxy management is handled securely at the server level
        $middleware->trustProxies(at: '*');

        $middleware->prepend(RequireHttps::class);

        $middleware->alias([
            'throttle' => ThrottleRequests::class,
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Render JSON exception responses for API routes or when JSON is expected
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request): bool => $request->is('api/*') || $request->expectsJson(),
        );

        // When debug mode is enabled, we'll use Laravel's default exception handler for detailed error output
        if (Config::boolean('app.debug')) {
            return;
        }

        // Override default error responses to conceal framework details and reduce risk of framework fingerprinting
        $exceptions->respond(function (Response $response, Exception $exception, Request $request) {
            // Remap sensitive HTTP status codes to generic ones
            $mask = [
                401 => 403,
                402 => 403,
                405 => 404,
                419 => 403,
            ];

            // Allowlist of HTTP codes that we can return with their generic error messages
            $genericErrorResponses = [
                403 => 'Forbidden.',
                404 => "Whoops! We couldn't find that page.",
                418 => 'I am a teapot.',
                429 => 'Too many requests.',
                500 => 'Internal server error.',
                503 => 'Service unavailable.',
            ];

            // Determine the HTTP status code and error message we should return
            $code = $mask[$response->getStatusCode()] ?? $response->getStatusCode();
            $message = $genericErrorResponses[$code] ?? null;

            // Fall back to a generic 500 response if the status code is not in the allowlist
            if ($message === null) {
                $code = 500;
                $message = $genericErrorResponses[500];
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], $code);
            }

            return response()->view('errors.' . $code, ['exception' => $exception], $code);
        });
    })->create();
