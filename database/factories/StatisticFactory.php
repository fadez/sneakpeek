<?php

namespace Database\Factories;

use App\Models\Statistic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistic>
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
            ]),
            'value' => $this->faker->numberBetween(0, 1000000),
        ];
    }

    /**
     * Indicate that the statistic is for created secrets count.
     */
    public function secretsCreated(int $value = 0): static
    {
        return $this->state([
            'key' => Statistic::KEY_SECRETS_CREATED,
            'value' => $value,
        ]);
    }

    /**
     * Indicate that the statistic is for revealed secrets count.
     */
    public function secretsRevealed(int $value = 0): static
    {
        return $this->state([
            'key' => Statistic::KEY_SECRETS_REVEALED,
            'value' => $value,
        ]);
    }
}
