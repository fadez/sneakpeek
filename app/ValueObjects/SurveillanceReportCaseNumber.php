<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Stringable;

/**
 * A surveillance report case number.
 */
final readonly class SurveillanceReportCaseNumber implements Stringable
{
    /**
     * Create a new value object instance.
     */
    public function __construct(
        public string $value,
    ) {
        //
    }

    /**
     * Get the string representation of the surveillance report case number.
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
