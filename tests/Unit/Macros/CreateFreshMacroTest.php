<?php

declare(strict_types=1);

use App\Models\Secret;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;

test('createFresh returns a single model when creating one model', function () {
    $model = Secret::factory()->createFresh();

    expect($model)->toBeInstanceOf(Secret::class);
});

test('createFresh returns a collection when creating multiple models', function () {
    $models = Secret::factory()->count(3)->createFresh();

    expect($models)->toBeInstanceOf(DatabaseCollection::class)
        ->and($models)->toHaveCount(3);
});
