<?php

use App\Enums\StatisticKey;
use App\Events\StatisticUpdated;
use App\Models\Statistic;
use App\Services\StatisticService;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->statisticService = resolve(StatisticService::class);
});

it('returns zero for a key that does not exist', function () {
    expect($this->statisticService->getValue(StatisticKey::SecretsCreated))->toBe(0);
});

it('returns the value for an existing key', function () {
    Statistic::factory()->secretsCreated(10)->createFresh();

    expect($this->statisticService->getValue(StatisticKey::SecretsCreated))->toBe(10);
});

it('increments value for an existing key', function () {
    Statistic::factory()->secretsCreated(1)->createFresh();

    $this->statisticService->incrementValue(StatisticKey::SecretsCreated);

    expect($this->statisticService->getValue(StatisticKey::SecretsCreated))->toBe(2);
});

it('creates a new record when incrementing a key that does not exist', function () {
    $this->statisticService->incrementValue(StatisticKey::SecretsCreated);

    expect($this->statisticService->getValue(StatisticKey::SecretsCreated))->toBe(1);
});

it('increments value by a custom amount', function () {
    Statistic::factory()->secretsExpired(10)->createFresh();

    $this->statisticService->incrementValue(StatisticKey::SecretsExpired, 15);

    expect($this->statisticService->getValue(StatisticKey::SecretsExpired))->toBe(25);
});

it('does nothing when amount is zero or negative', function () {
    Statistic::factory()->secretsCreated(10)->createFresh();

    $this->statisticService->incrementValue(StatisticKey::SecretsCreated, 0);
    $this->statisticService->incrementValue(StatisticKey::SecretsCreated, -1);

    expect($this->statisticService->getValue(StatisticKey::SecretsCreated))->toBe(10);
});

it('dispatches StatisticUpdated event when incrementing', function () {
    Event::fake();

    $this->statisticService->incrementValue(StatisticKey::SecretsCreated);

    Event::assertDispatched(StatisticUpdated::class);
});

it('returns a snapshot with all statistic keys', function () {
    Statistic::factory()->secretsCreated(100)->createFresh();
    Statistic::factory()->secretsRevealed(75)->createFresh();
    Statistic::factory()->secretsExpired(25)->createFresh();
    Statistic::factory()->secretsBurned(1)->createFresh();

    $data = $this->statisticService->getSnapshot();

    expect($data)->toBe([
        'secrets_created' => 100,
        'secrets_revealed' => 75,
        'secrets_expired' => 25,
        'secrets_burned' => 1,
    ]);
});

it('returns zeros in snapshot when no statistics exist', function () {
    $data = $this->statisticService->getSnapshot();

    expect($data)->toBe([
        'secrets_created' => 0,
        'secrets_revealed' => 0,
        'secrets_expired' => 0,
        'secrets_burned' => 0,
    ]);
});
