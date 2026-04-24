<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleRequests extends BaseThrottleRequests
{
    /**
     * Get the limit headers information.
     *
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return array<never, never>
     */
    protected function getHeaders(
        $maxAttempts, // @pest-ignore-type
        $remainingAttempts, // @pest-ignore-type
        $retryAfter = null, // @pest-ignore-type
        ?Response $response = null
    ): array {
        // Remove X-RateLimit-* and Retry-After headers to ensure security through obscurity
        return [];
    }
}
