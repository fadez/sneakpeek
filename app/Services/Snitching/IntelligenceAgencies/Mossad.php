<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\ShouldSendSurveillanceReports;
use App\DTOs\SurveillanceReport;
use Override;

/**
 * Let's assume Mossad uses an API to accept reports.
 */
final readonly class Mossad extends IntelligenceAgency implements ShouldSendSurveillanceReports
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'Mossad';
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
        //     ->post('https://reports.mossad.test', $report->toArray())
        //     ->throw();
    }
}
