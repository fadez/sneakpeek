<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\StatisticKey;
use App\Events\StatisticsUpdated;
use App\Models\Statistic;
use Illuminate\Support\Facades\DB;

/**
 * A centralized service for accessing and updating application statistics.
 */
final readonly class StatisticService
{
    /**
     * Aggregate all application statistics into a single snapshot.
     *
     * @return array<string, int>
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
        $statistic = Statistic::where('key', $key->value)->first();

        return $statistic->value ?? 0;
    }

    /**
     * Increment a statistic key by the given amount.
     *
     * The operation is performed atomically using upsert to prevent race conditions.
     *
     * This method is supported only by SQLite and PostgreSQL database drivers.
     */
    public function incrementValue(StatisticKey $key, int $amount = 1): void
    {
        if ($amount <= 0) {
            return;
        }

        /** @var literal-string $qualifiedValueColumn */
        $qualifiedValueColumn = (new Statistic)->qualifyColumn('value');

        Statistic::upsert(
            [['key' => $key->value, 'value' => $amount]],
            uniqueBy: ['key'],
            update: ['value' => DB::raw($qualifiedValueColumn . ' + excluded.value')],
        );

        event(new StatisticsUpdated($this->getSnapshot()));
    }
}
