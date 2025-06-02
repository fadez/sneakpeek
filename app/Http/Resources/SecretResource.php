<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $showSecretKey = $request->routeIs('api.secrets.store');
        $showRevealedAt = $request->routeIs('api.secrets.store', 'api.secrets.receipt');
        $showExpiresAt = $request->routeIs('api.secrets.store', 'api.secrets.receipt');

        return [
            'key' => $this->key,
            'secret_key' => $this->when($showSecretKey, $this->secret_key),
            'expires_at' => $this->when($showExpiresAt, $this->expires_at),
            'revealed_at' => $this->when($showRevealedAt, $this->revealed_at),
            'is_passphrase_protected' => $this->is_passphrase_protected,
            'is_expired' => $this->is_expired,
            'is_revealed' => $this->is_revealed,
        ];
    }
}
