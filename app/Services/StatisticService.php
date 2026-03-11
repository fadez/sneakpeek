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
        return [
            'secrets_created' => $this->getValue(Statistic::KEY_SECRETS_CREATED),
            'secrets_revealed' => $this->getValue(Statistic::KEY_SECRETS_REVEALED),
            'secrets_expired' => $this->getValue(Statistic::KEY_SECRETS_EXPIRED),
            'secrets_burned' => $this->getValue(Statistic::KEY_SECRETS_BURNED),
        ];
    }

    public function getValue(string $key): int
    {
        $statistic = Statistic::where('key', $key)->first();

        return $statistic->value ?? 0;
    }

    public function incrementValue(string $key, int $amount = 1): void
    {
        if ($amount <= 0) {
            return;
        }

        Statistic::firstOrCreate(['key' => $key])->increment('value', $amount);

        event(new StatisticUpdated);
    }
}
