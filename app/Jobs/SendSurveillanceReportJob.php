<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\AcceptsSurveillanceReports;
use App\DTOs\SurveillanceReport;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\Attributes\UniqueFor;

/**
 * Idempotent job that handles sending the surveillance report.
 *
 * Retries indefinitely because no agency would forgive a missed report, so failure is not an option here.
 *
 * Exponential backoff spaces out retries to give transient failures time to resolve.
 */
#[Tries(0)]
#[Backoff([1, 10, 30, 300, 3600])]
#[UniqueFor(86400)]
final class SendSurveillanceReportJob implements ShouldBeEncrypted, ShouldBeUnique, ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly SurveillanceReport $report,
    ) {
        //
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->report->caseNumber->value;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! $this->report->agency instanceof AcceptsSurveillanceReports) {
            return;
        }

        if ($this->report->agency->hasSentSurveillanceReport($this->report)) {
            return;
        }

        // Send the report first before marking it as sent, because silent non-delivery is the worse failure mode here
        $this->report->agency->sendSurveillanceReport($this->report);

        // A race between two concurrent calls for the same report to mark it as sent will be silently skipped
        // instead of throwing an exception that would retry this whole job
        $this->report->agency->markSurveillanceReportAsSent($this->report);
    }
}
