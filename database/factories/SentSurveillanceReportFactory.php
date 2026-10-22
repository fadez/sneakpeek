<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SentSurveillanceReport;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SentSurveillanceReport>
 */
class SentSurveillanceReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agencyId = Str::upper(fake()->lexify('???'));

        return [
            'agency_id' => $agencyId,
            'case_number' => $agencyId . '-' . Str::uuid(),
            'sent_at' => fake()->dateTimeBetween('-1 hour', 'now'),
        ];
    }
}
