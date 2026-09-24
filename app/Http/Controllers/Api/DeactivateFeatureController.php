<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Requests\DeactivateFeatureRequest;
use Illuminate\Http\Response;
use Laravel\Pennant\Feature;

final readonly class DeactivateFeatureController
{
    /**
     * Deactivate a feature flag for the active session.
     */
    public function __invoke(DeactivateFeatureRequest $request): Response
    {
        Feature::deactivate($request->string('feature')->value());

        return response()->noContent();
    }
}
