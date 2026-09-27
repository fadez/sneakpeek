<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTOs\SurveillanceReport;

/**
 * Classes that implement this interface are able to accept surveillance reports from us.
 */
interface AcceptsSurveillanceReports
{
    /**
     * Send the surveillance report.
     */
    public function sendSurveillanceReport(SurveillanceReport $report): void;
}
