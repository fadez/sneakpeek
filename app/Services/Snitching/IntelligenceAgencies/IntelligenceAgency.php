<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use App\Contracts\AcceptsSurveillanceReports;
use App\Contracts\ShouldSendSurveillanceReports;
use App\DTOs\SurveillanceReport;
use App\Models\SentSurveillanceReport;
use App\ValueObjects\SurveillanceReportCaseNumber;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;

/**
 * A base class for a fictional intelligence agency, providing shared logic for generating and tracking surveillance reports.
 *
 * An agency can accept surveillance reports by implementing the AcceptsSurveillanceReports contract.
 * However, surveillance reports won't be sent out automatically, in order for that to happen, the agency must implement the ShouldSendSurveillanceReports contract instead.
 *
 * @see AcceptsSurveillanceReports
 * @see ShouldSendSurveillanceReports
 */
abstract readonly class IntelligenceAgency
{
    /**
     * Get the agency's official abbreviation.
     */
    abstract public function abbreviation(): string;

    /**
     * Get the agency's ID.
     */
    final public function id(): string
    {
        return Str::upper($this->abbreviation());
    }

    /**
     * Make a new surveillance report.
     */
    final public function makeSurveillanceReport(string $content): SurveillanceReport
    {
        return new SurveillanceReport(
            agency: $this,
            caseNumber: $this->generateSurveillanceReportCaseNumber(),
            content: $content,
        );
    }

    /**
     * Determine if the surveillance report has already been sent.
     */
    final public function hasSentSurveillanceReport(SurveillanceReport $report): bool
    {
        return SentSurveillanceReport::query()
            ->where('agency_id', $report->agency->id())
            ->where('case_number', $report->caseNumber->value)
            ->exists();
    }

    /**
     * Mark the surveillance report as sent.
     */
    final public function markSurveillanceReportAsSent(SurveillanceReport $report): void
    {
        try {
            SentSurveillanceReport::create([
                'agency_id' => $report->agency->id(),
                'case_number' => $report->caseNumber->value,
                'sent_at' => now(),
            ]);
        } catch (UniqueConstraintViolationException) {
            // A concurrent call for the same report has already marked it as sent
        }
    }

    /**
     * Generate a case number for a surveillance report.
     */
    protected function generateSurveillanceReportCaseNumber(): SurveillanceReportCaseNumber
    {
        return new SurveillanceReportCaseNumber($this->id() . '-' . Str::uuid());
    }
}
