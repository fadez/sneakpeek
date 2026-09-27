<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Classes that implement this interface will be sent surveillance reports automatically.
 */
interface ShouldSendSurveillanceReports extends AcceptsSurveillanceReports
{
    //
}
