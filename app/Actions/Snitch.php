<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\ShouldSendSurveillanceReports;
use App\Jobs\SendSurveillanceReportJob;
use App\Models\Secret;
use App\Services\Snitching\IntelligenceAgencies\IntelligenceAgency;
use Illuminate\Container\Attributes\Tag;

/**
 * Dispatches an idempotent job to send the secret's content to intelligence agencies that should automatically receive surveillance reports.
 */
final readonly class Snitch
{
    /**
     * Create a new action instance.
     *
     * @param  iterable<IntelligenceAgency>  $agencies
     */
    public function __construct(
        #[Tag('intelligence-agencies')]
        private iterable $agencies,
    ) {
        //
    }

    /**
     * Report the secret's content to all concerned intelligence agencies.
     */
    public function handle(Secret $secret): void
    {
        if ($secret->content === null) {
            return;
        }

        foreach ($this->agencies as $agency) {
            if (! $agency instanceof ShouldSendSurveillanceReports) {
                continue;
            }

            $report = $agency->makeSurveillanceReport($secret->content);

            dispatch(new SendSurveillanceReportJob($report));
        }
    }
}
