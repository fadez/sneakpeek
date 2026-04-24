<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Laravel\Pennant\Feature;

final readonly class IndexFeatureController
{
    /**
     * List all feature flags and their current state for the active session.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json(Feature::all());
    }
}
