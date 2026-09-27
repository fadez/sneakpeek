<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\ShouldSendSurveillanceReports;
use App\DTOs\SurveillanceReport;
use Override;

/**
 * Let's assume CIA uses an API to accept reports.
 */
final readonly class Cia extends IntelligenceAgency implements ShouldSendSurveillanceReports
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'CIA';
    }

    /**
     * Send the surveillance report.
     */
    #[Override]
    public function sendSurveillanceReport(SurveillanceReport $report): void
    {
        // Simulate sending an API request to the agency...
        // \Illuminate\Support\Facades\Http::withHeaders([
        //     'Idempotency-Key' => $report->caseNumber->value,
        // ])
        //     ->post('https://tips.cia.test', $report->toArray())
        //     ->throw();
    }
}
