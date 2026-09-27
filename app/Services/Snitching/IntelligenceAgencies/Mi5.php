<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\AcceptsSurveillanceReports;
use App\DTOs\SurveillanceReport;
use Override;

/**
 * Let's assume we are not required to send reports to MI5, but if needed, we could do so via a webhook.
 */
final readonly class Mi5 extends IntelligenceAgency implements AcceptsSurveillanceReports
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'MI5';
    }

    /**
     * Send the surveillance report.
     */
    #[Override]
    public function sendSurveillanceReport(SurveillanceReport $report): void
    {
        // Simulate sending a webhook request to the agency...
        // \Illuminate\Support\Facades\Http::withHeaders([
        //     'Idempotency-Key' => $report->caseNumber->value,
        // ])
        //     ->post('https://hooks.mi5.com/reports/SNEAKPEEEK/SECRET/00000000123', $report->toArray())
        //     ->throw();
    }
}
