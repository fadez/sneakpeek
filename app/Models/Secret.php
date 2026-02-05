<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 * @property-read bool $is_passphrase_protected
 * @property-read bool $is_expired
 * @property-read bool $is_revealed
 */
class Secret extends Model
{
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
        'is_passphrase_protected', 'is_expired', 'is_revealed',
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
        return $query->where(function ($query) {
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
     * Verify if the provided passphrase matches the hashed passphrase.
     */
    public function checkPassphrase(?string $passphrase = null): bool
    {
        // Return true if the secret doesn't require a passphrase
        if ($this->passphrase === null) {
            return true;
        }

        // Return false if a passphrase is not provided when the secret requires one
        if ($passphrase === null) {
            return false;
        }

        return Hash::check($passphrase, $this->passphrase);
    }

    /**
     * Wipe the content of the secret from the database permanently.
     */
    public function wipeContent(): void
    {
        $this->content = null;
        $this->save();
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
}
