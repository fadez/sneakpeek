<?php

declare(strict_types=1);

use App\Enums\StatisticKey;
use App\Events\StatisticsUpdated;
use App\Models\Statistic;
use App\Services\StatisticService;
use Illuminate\Support\Facades\Event;

it('returns zero for a key that does not exist', function () {
    expect(new StatisticService()->getValue(StatisticKey::SecretsCreated))->toBe(0);
});

it('creates exactly one record when incrementing a key that does not exist', function () {
    $statisticService = new StatisticService;

    $statisticService->incrementValue(StatisticKey::SecretsCreated);

    $rowCount = Statistic::query()->where('key', StatisticKey::SecretsCreated)->count();
    $value = $statisticService->getValue(StatisticKey::SecretsCreated);

    expect($rowCount)->toBe(1)
        ->and($value)->toBe(1);
});

it('does not create duplicate rows for the same key', function () {
    $statisticService = new StatisticService;

    $statisticService->incrementValue(StatisticKey::SecretsCreated);
    $statisticService->incrementValue(StatisticKey::SecretsCreated);
    $statisticService->incrementValue(StatisticKey::SecretsCreated);

    $rowCount = Statistic::query()->where('key', StatisticKey::SecretsCreated)->count();
    $value = $statisticService->getValue(StatisticKey::SecretsCreated);

    expect($rowCount)->toBe(1)
        ->and($value)->toBe(3);
});

it('returns the value for an existing key', function () {
    Statistic::factory()->secretsCreated(10)->createFresh();

    expect(new StatisticService()->getValue(StatisticKey::SecretsCreated))->toBe(10);
});

it('increments value for an existing key', function () {
    Statistic::factory()->secretsCreated(1)->createFresh();

    $statisticService = new StatisticService;

    $statisticService->incrementValue(StatisticKey::SecretsCreated);

    expect($statisticService->getValue(StatisticKey::SecretsCreated))->toBe(2);
});

it('increments value by a custom amount', function () {
    Statistic::factory()->secretsExpired(10)->createFresh();

    $statisticService = new StatisticService;

    $statisticService->incrementValue(StatisticKey::SecretsExpired, 15);

    expect($statisticService->getValue(StatisticKey::SecretsExpired))->toBe(25);
});

it('does nothing when amount is zero or negative', function () {
    Statistic::factory()->secretsCreated(10)->createFresh();

    $statisticService = new StatisticService;

    $statisticService->incrementValue(StatisticKey::SecretsCreated, 0);
    $statisticService->incrementValue(StatisticKey::SecretsCreated, -1);

    expect($statisticService->getValue(StatisticKey::SecretsCreated))->toBe(10);
});

it('dispatches StatisticsUpdated event when incrementing', function () {
    Event::fake();

    new StatisticService()->incrementValue(StatisticKey::SecretsCreated);

    Event::assertDispatched(StatisticsUpdated::class);
});

it('returns a snapshot with all statistic keys', function () {
    Statistic::factory()->secretsCreated(100)->createFresh();
    Statistic::factory()->secretsRevealed(75)->createFresh();
    Statistic::factory()->secretsExpired(25)->createFresh();
    Statistic::factory()->secretsBurned(1)->createFresh();

    $data = new StatisticService()->getSnapshot();

    expect($data)->toBe([
        'secrets_created' => 100,
        'secrets_revealed' => 75,
        'secrets_expired' => 25,
        'secrets_burned' => 1,
    ]);
});

it('returns zeros in snapshot when no statistics exist', function () {
    $data = new StatisticService()->getSnapshot();

    expect($data)->toBe([
        'secrets_created' => 0,
        'secrets_revealed' => 0,
        'secrets_expired' => 0,
        'secrets_burned' => 0,
    ]);
});
