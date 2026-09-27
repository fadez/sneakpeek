<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Secret;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\JsonApi\JsonApiResource;

/**
 * @mixin Secret
 *
 * @phpstan-type JsonApiResourceData array{data: array{id: string, type: string, attributes: object}}
 */
final class SecretResource extends JsonApiResource
{
    /**
     * The access token, set only when the secret was just created.
     */
    private ?string $accessToken = null;

    /**
     * Include the access token in the attributes.
     */
    public function withAccessToken(string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the resource's attributes.
     *
     * @return array<string, mixed>
     */
    public function toAttributes(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'created_at' => $this->created_at,
            'expires_at' => $this->expires_at,
            'revealed_at' => $this->revealed_at,
            'is_available' => $this->is_available,
            'is_expired' => $this->is_expired,
            'is_passphrase_protected' => $this->is_passphrase_protected,
            'is_revealed' => $this->is_revealed,
            'access_token' => $this->when($this->accessToken !== null, $this->accessToken),
        ];
    }
}
