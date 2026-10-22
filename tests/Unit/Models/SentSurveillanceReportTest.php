<?php

declare(strict_types=1);

use App\Models\SentSurveillanceReport;
use Carbon\CarbonImmutable;

test('toArray', function () {
    $model = SentSurveillanceReport::factory()->createFresh();

    expect(array_keys($model->toArray()))->toBe([
        'id',
        'agency_id',
        'case_number',
        'sent_at',
    ]);
});

test('cast: sent_at', function () {
    $report = SentSurveillanceReport::factory()->createFresh();

    expect($report->sent_at)->toBeInstanceOf(CarbonImmutable::class);
});
