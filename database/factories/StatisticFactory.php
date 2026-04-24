<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StatisticKey;
use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Statistic>
 */
class StatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->randomElement([
                StatisticKey::SecretsCreated,
                StatisticKey::SecretsRevealed,
                StatisticKey::SecretsExpired,
                StatisticKey::SecretsBurned,
            ]),
            'value' => $this->faker->numberBetween(0, 1000000),
        ];
    }

    /**
     * Indicate that the statistic is for created secrets.
     */
    public function secretsCreated(int $value = 0): static
    {
        return $this->state([
            'key' => StatisticKey::SecretsCreated,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for revealed secrets.
     */
    public function secretsRevealed(int $value = 0): static
    {
        return $this->state([
            'key' => StatisticKey::SecretsRevealed,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for expired secrets.
     */
    public function secretsExpired(int $value = 0): static
    {
        return $this->state([
            'key' => StatisticKey::SecretsExpired,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for burned secrets.
     */
    public function secretsBurned(int $value = 0): static
    {
        return $this->state([
            'key' => StatisticKey::SecretsBurned,
            'value' => $value,
        ]);
    }
}
