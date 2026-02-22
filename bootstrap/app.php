<?php

use App\Http\Middleware\ThrottleRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        // Disable default health check to prevent framework identification
        // health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'throttle' => ThrottleRequests::class,
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Only enable default Laravel error handling on local environments or when debug is true
        if (! app()->environment('local') && config('app.debug', false) !== true) {
            // Customize the default error handling to prevent framework identification
            $exceptions->respond(function ($response) {
                $currentStatusCode = $response->getStatusCode();

                // List of HTTP error codes that have custom JSON message responses
                $messages = [
                    403 => 'Forbidden.',
                    404 => 'Whoops! We couldn\'t find that page.',
                    418 => 'I am a teapot.',
                    429 => 'Too many requests.',
                    500 => 'Internal server error.',
                    503 => 'Service unavailable.',
                ];

                // List of HTTP error codes that should be mapped to other error codes
                $mask = [
                    401 => 403,
                    402 => 403,
                    405 => 404,
                    419 => 403,
                ];

                // Determine the final status code
                $code = $mask[$currentStatusCode] ?? (isset($messages[$currentStatusCode]) ? $currentStatusCode : 500);
                $message = $messages[$code];

                if (request()->expectsJson()) {
                    return response()->json(['message' => $message], $code);
                }

                return response()->view('errors.' . $code, [], $code);
            });
        }
    })->create();
