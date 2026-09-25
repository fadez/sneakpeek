<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\SecretFactory;
use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * @property-read string $id
 * @property-read string $access_token
 * @property-read string|null $content
 * @property-read string|null $passphrase
 * @property-read CarbonImmutable $expires_at
 * @property-read CarbonImmutable|null $revealed_at
 * @property-read CarbonImmutable|null $created_at
 * @property-read CarbonImmutable|null $updated_at
 * @property-read bool $is_available
 * @property-read bool $is_expired
 * @property-read bool $is_passphrase_protected
 * @property-read bool $is_revealed
 */
#[Table(key: 'id', keyType: 'string', incrementing: false)]
#[Appends(['is_available', 'is_expired', 'is_passphrase_protected', 'is_revealed'])]
#[Hidden(['access_token', 'content', 'passphrase'])]
final class Secret extends Model
{
    /** @use HasFactory<SecretFactory> */
    use HasFactory;

    use Prunable;

    /**
     * Length of generated secure tokens, in characters.
     *
     * 64 characters generated from a 62-character pool (a–z, A–Z, 0–9) give approximately 381 bits of entropy,
     * which is much better than, for example, a UUID v4, which has only 122 bits of entropy.
     */
    public const int TOKEN_LENGTH = 64;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'access_token' => 'hashed',
            'content' => 'encrypted',
            'passphrase' => 'hashed',
            'expires_at' => 'immutable_datetime',
            'revealed_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
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
        return $query->where('expires_at', '<', now());
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
        return $query->where('expires_at', '>=', now());
    }

    /**
     * Scope a query to only include secrets whose content has not been wiped.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeHasContent(Builder $query): Builder
    {
        return $query->whereNotNull('content');
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
     * Scope a query to only include unrevealed secrets that haven't expired.
     *
     * @param  Builder<Secret>  $query
     * @return Builder<Secret>
     */
    #[Scope]
    protected function scopeAvailable(Builder $query): Builder
    {
        return $query->hasContent()->unrevealed()->notExpired();
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
     * Get the prunable model query.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return self::query()->where('expires_at', '<', now()->minus(days: 90));
    }

    /**
     * Determine if the secret is still available.
     *
     * @return Attribute<bool, never>
     */
    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => ! $this->is_expired && ! $this->is_revealed && $this->content !== null,
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
            get: fn () => $this->expires_at->isPast(),
        );
    }

    /**
     * Determine if the secret is protected by a passphrase.
     *
     * @return Attribute<bool, never>
     */
    protected function isPassphraseProtected(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->passphrase !== null,
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
            get: fn (): bool => $this->revealed_at !== null,
        );
    }
}
