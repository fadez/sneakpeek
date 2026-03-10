<?php

use App\Models\Statistic;

test('toArray', function () {
    $model = Statistic::factory()->createFresh();

    expect(array_keys($model->toArray()))->toBe([
        'key',
        'value',
        'created_at',
        'updated_at',
    ]);
});
