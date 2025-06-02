<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Secret extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key', 'secret_key', 'content', 'passphrase', 'expires_at', 'revealed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<string>
     */
    protected $hidden = [
        'secret_key', 'content', 'passphrase',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
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
     * Scope a query to only include available secrets.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        })
            ->whereNull('revealed_at');
    }

    /**
     * Scope a query to only include expired secrets.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '<', now());
    }

    /**
     * Scope a query to only include expired secrets that need to be wiped.
     */
    public function scopeToBeWiped(Builder $query): Builder
    {
        return $query->whereNotNull('content')->expired();
    }

    /**
     * Verify if the passphrase matches the hashed passphrase.
     */
    public function checkPassphrase(?string $passphrase = null): bool
    {
        if ($this->passphrase === null) {
            return true;
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
     * Check if the secret is passphrase protected.
     */
    public function isPassphraseProtected(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->passphrase !== null,
        );
    }

    /**
     * Check if the secret has expired.
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->expires_at !== null && $this->expires_at < now(),
        );
    }

    /**
     * Check if the secret has been revealed.
     */
    public function isRevealed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->revealed_at !== null,
        );
    }
}
