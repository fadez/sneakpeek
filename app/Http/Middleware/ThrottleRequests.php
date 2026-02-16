<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests extends BaseThrottleRequests
{
    /**
     * Get the limit headers information.
     *
     * @param  int  $maxAttempts
     * @param  int  $remainingAttempts
     * @param  int|null  $retryAfter
     * @return array<string, int>
     */
    protected function getHeaders($maxAttempts,
        $remainingAttempts,
        $retryAfter = null,
        ?Response $response = null)
    {
        // Remove X-RateLimit-* and Retry-After headers to ensure security through obscurity
        return [];
    }
}
