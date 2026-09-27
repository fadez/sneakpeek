<?php

declare(strict_types=1);

namespace App\Services\Snitching\IntelligenceAgencies;

use Override;

/**
 * Let's assume we no longer work with MI6 and thus no longer send reports to them.
 */
final readonly class Mi6 extends IntelligenceAgency
{
    /**
     * Get the agency's official abbreviation.
     */
    #[Override]
    public function abbreviation(): string
    {
        return 'MI6';
    }
}
