<?php

namespace App\Models;

use Database\Factories\StatisticFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistic extends Model
{
    /** @use HasFactory<StatisticFactory> */
    use HasFactory;

    public const KEY_SECRETS_CREATED = 'secrets_created';

    public const KEY_SECRETS_REVEALED = 'secrets_revealed';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'key';

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
