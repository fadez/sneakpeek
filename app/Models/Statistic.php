<?php

namespace App\Models;

use Database\Factories\StatisticFactory;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Table(key: 'key', keyType: 'string', incrementing: false)]
class Statistic extends Model
{
    /** @use HasFactory<StatisticFactory> */
    use HasFactory;

    public const KEY_SECRETS_CREATED = 'secrets_created';

    public const KEY_SECRETS_REVEALED = 'secrets_revealed';

    public const KEY_SECRETS_EXPIRED = 'secrets_expired';

    public const KEY_SECRETS_BURNED = 'secrets_burned';

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
