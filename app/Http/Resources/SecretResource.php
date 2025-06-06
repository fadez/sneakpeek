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
        $isReceiptOrStoreRoute = $request->routeIs('api.secrets.store', 'api.secrets.receipt');

        return [
            'key' => $this->key,
            $this->mergeWhen($isReceiptOrStoreRoute, [
                'secret_key' => $this->secret_key,
                'expires_at' => $this->expires_at,
                'revealed_at' => $this->revealed_at,
            ]),
            'is_passphrase_protected' => $this->is_passphrase_protected,
            'is_expired' => $this->is_expired,
            'is_revealed' => $this->is_revealed,
        ];
    }
}
