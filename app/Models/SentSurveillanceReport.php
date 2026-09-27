<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property-read string $agency_id
 * @property-read string $case_number
 * @property-read CarbonImmutable $sent_at
 */
#[Table(name: 'sent_surveillance_reports', key: 'id', keyType: 'string', incrementing: false, timestamps: false)]
final class SentSurveillanceReport extends Model
{
    use HasUuids;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'agency_id' => 'string',
            'case_number' => 'string',
            'sent_at' => 'immutable_datetime',
        ];
    }
}
