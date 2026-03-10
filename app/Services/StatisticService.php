<?php

namespace App\Services;

use App\Events\StatisticUpdated;
use App\Models\Statistic;

class StatisticService
{
    /**
     * @return array<string, mixed>
     */
    public function getSnapshot(): array
    {
        $createdCount = $this->getValue(Statistic::KEY_SECRETS_CREATED);
        $revealedCount = $this->getValue(Statistic::KEY_SECRETS_REVEALED);

        return [
            'secrets_created' => $createdCount,
            'secrets_revealed' => $revealedCount,
            'reveal_rate' => $createdCount > 0 ? round($revealedCount / $createdCount * 100, 2) : 0.0,
        ];
    }

    public function getValue(string $key): int
    {
        $statistic = Statistic::where('key', $key)->first();

        return $statistic->value ?? 0;
    }

    public function incrementValue(string $key): void
    {
        Statistic::firstOrCreate(['key' => $key])->increment('value');

        event(new StatisticUpdated);
    }
}
