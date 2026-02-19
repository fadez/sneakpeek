<?php

namespace App\Models;

use Database\Factories\SecretFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $key
 * @property string $secret_key
 * @property string|null $content
 * @property string|null $passphrase
 * @property Carbon|null $expires_at
 * @property Carbon|null $revealed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read bool $is_passphrase_protected
 * @property-read bool $is_expired
 * @property-read bool $is_revealed
 * @property-read bool $is_active
 */
class Secret extends Model
{
    /** @use HasFactory<SecretFactory> */
    use HasFactory;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'secret_key', 'content', 'passphrase',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var list<string>
     */
    protected $appends = [
        'is_passphrase_protected', 'is_expired', 'is_revealed', 'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'encrypted',
            'passphrase' => 'hashed',
            'expires_at' => 'datetime',
            'revealed_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include unrevealed secrets that haven't expired.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeActive(Builder $query): Builder
    {
        return $query->unrevealed()->notExpired();
    }

    /**
     * Scope a query to only include expired secrets.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    /**
     * Scope a query to only include secrets that haven't expired.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        });
    }

    /**
     * Scope a query to only include unrevealed secrets.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeUnrevealed(Builder $query): Builder
    {
        return $query->whereNull('revealed_at');
    }

    /**
     * Scope a query to only include expired secrets that need to be wiped.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeToBeWiped(Builder $query): Builder
    {
        return $query->whereNotNull('content')->expired();
    }

    /**
     * Determine if the secret is protected by a passphrase.
     *
     * @return Attribute<bool, never>
     */
    protected function isPassphraseProtected(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->passphrase !== null,
        );
    }

    /**
     * Determine if the secret is unrevealed and haven't expired.
     *
     * @return Attribute<bool, never>
     */
    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this->is_expired && ! $this->is_revealed,
        );
    }

    /**
     * Determine if the secret has been revealed.
     *
     * @return Attribute<bool, never>
     */
    protected function isRevealed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->revealed_at !== null,
        );
    }

    /**
     * Determine if the secret has expired.
     *
     * @return Attribute<bool, never>
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->expires_at !== null && $this->expires_at < now(),
        );
    }
}
