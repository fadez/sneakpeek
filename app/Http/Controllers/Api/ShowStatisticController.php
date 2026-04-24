<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Services\StatisticService;
use Illuminate\Http\JsonResponse;

final readonly class ShowStatisticController
{
    /**
     * Get a snapshot of the current application statistics.
     */
    public function __invoke(StatisticService $service): JsonResponse
    {
        return response()->json($service->getSnapshot());
    }
}
