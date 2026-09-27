<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\ShouldSendSurveillanceReports;
use App\DTOs\SurveillanceReport;
use Override;

/**
 * Let's assume NSA intercepted the data before it even reached our server, so we don't need to actually send the report.
 */
final readonly class Nsa extends IntelligenceAgency implements ShouldSendSurveillanceReports
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'NSA';
    }

    /**
     * Send the surveillance report.
     */
    #[Override]
    public function sendSurveillanceReport(SurveillanceReport $report): void
    {
        // The NSA has already intercepted, decrypted, and stored the data themselves, so sending them a copy would be redundant and, possibly, insulting...
    }
}
