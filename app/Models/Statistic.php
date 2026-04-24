<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StatisticFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            'value' => 'integer',
        ];
    }
}
