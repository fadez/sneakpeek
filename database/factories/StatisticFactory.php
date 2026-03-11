<?php

namespace Database\Factories;

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
                Statistic::KEY_SECRETS_CREATED,
                Statistic::KEY_SECRETS_REVEALED,
                Statistic::KEY_SECRETS_EXPIRED,
                Statistic::KEY_SECRETS_BURNED,
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
            'key' => Statistic::KEY_SECRETS_CREATED,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for revealed secrets.
     */
    public function secretsRevealed(int $value = 0): static
    {
        return $this->state([
            'key' => Statistic::KEY_SECRETS_REVEALED,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for expired secrets.
     */
    public function secretsExpired(int $value = 0): static
    {
        return $this->state([
            'key' => Statistic::KEY_SECRETS_EXPIRED,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for burned secrets.
     */
    public function secretsBurned(int $value = 0): static
    {
        return $this->state([
            'key' => Statistic::KEY_SECRETS_BURNED,
            'value' => $value,
        ]);
    }
}
