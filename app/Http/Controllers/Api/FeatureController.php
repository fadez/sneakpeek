<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeactivateFeatureRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Laravel\Pennant\Feature;

class FeatureController extends Controller
{
    /**
     * List all feature flags and their current state for the active session.
     */
    public function index(): JsonResponse
    {
        return response()->json(Feature::all());
    }

    /**
     * Deactivate a feature flag for the active session.
     */
    public function deactivate(DeactivateFeatureRequest $request): Response
    {
        /** @var string $feature */
        $feature = $request->validated('feature');

        Feature::deactivate($feature);

        return response()->noContent();
    }
}
