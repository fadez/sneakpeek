<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Secret
 */
class SecretResource extends JsonResource
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string|null
     */
    public static $wrap = 'secret';

    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'expires_at' => $this->expires_at,
            'revealed_at' => $this->revealed_at,
            'is_passphrase_protected' => $this->is_passphrase_protected,
            'is_expired' => $this->is_expired,
            'is_revealed' => $this->is_revealed,
            'is_available' => $this->is_available,
        ];
    }
}
