<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StatisticKey;
use App\Events\StatisticUpdated;
use App\Models\Statistic;

final readonly class StatisticService
{
    /**
     * Aggregate all application statistics into a single snapshot.
     *
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        return [
            'secrets_created' => $this->getValue(StatisticKey::SecretsCreated),
            'secrets_revealed' => $this->getValue(StatisticKey::SecretsRevealed),
            'secrets_expired' => $this->getValue(StatisticKey::SecretsExpired),
            'secrets_burned' => $this->getValue(StatisticKey::SecretsBurned),
        ];
    }

    /**
     * Get the current value for a given statistic key.
     */
    public function getValue(StatisticKey $key): int
    {
        $statistic = Statistic::where('key', $key)->first();

        return $statistic->value ?? 0;
    }

    /**
     * Increment a statistic key by the given amount.
     */
    public function incrementValue(StatisticKey $key, int $amount = 1): void
    {
        if ($amount <= 0) {
            return;
        }

        Statistic::firstOrCreate(['key' => $key])->increment('value', $amount);

        event(new StatisticUpdated);
    }
}
