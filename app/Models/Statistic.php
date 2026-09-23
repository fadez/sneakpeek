<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\StatisticFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $key
 * @property-read int $value
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 */
#[Table(key: 'key', keyType: 'string', incrementing: false)]
final class Statistic extends Model
{
    /** @use HasFactory<StatisticFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'key' => 'string',
            'value' => 'integer',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
