<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\ShouldSendSurveillanceReports;
use App\DTOs\SurveillanceReport;
use App\ValueObjects\SurveillanceReportCaseNumber;
use Illuminate\Support\Str;
use Override;

/**
 * Let's assume FBI uses email to accept reports.
 */
final readonly class Fbi extends IntelligenceAgency implements ShouldSendSurveillanceReports
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'FBI';
    }

    /**
     * Generate a case number for a surveillance report.
     */
    #[Override]
    protected function generateSurveillanceReportCaseNumber(): SurveillanceReportCaseNumber
    {
        return new SurveillanceReportCaseNumber($this->id() . '-100-HQ-' . Str::uuid());
    }

    /**
     * Send the surveillance report.
     */
    #[Override]
    public function sendSurveillanceReport(SurveillanceReport $report): void
    {
        // Simulate sending an email to the agency...
        // \Illuminate\Support\Facades\Mail::raw(
        //     (string) $report,
        //     function ($message) use ($report): void {
        //         $message->to('tips@fbi.test')->subject($report->subject);
        //
        //         $message->getSymfonyMessage()
        //             ->getHeaders()
        //             ->addTextHeader('Idempotency-Key', $report->caseNumber->value);
        //     },
        // );
    }
}
