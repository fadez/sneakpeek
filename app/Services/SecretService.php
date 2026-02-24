<?php

namespace App\Services;

use App\Http\Requests\StoreSecretRequest;
use App\Models\Secret;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * This SecretService doesn't protect the president, but it does handle the business logic for secrets.
 */
class SecretService
{
    /**
     * Create a new secret from a request.
     */
    public function createFromRequest(StoreSecretRequest $request, string $accessToken): Secret
    {
        return Secret::create([
            'id' => Str::random(64),
            'access_token' => $accessToken,
            'content' => $request->input('content'),
            'passphrase' => $request->input('passphrase'),
            'expires_at' => now()->addSeconds($request->integer('ttl')),
        ]);
    }

    /**
     * Reveal a secret by returning its content and then permanently wipe its content from the database.
     *
     * The operation is performed atomically within a transaction, locking the row to prevent concurrent access.
     *
     * @throws ModelNotFoundException
     */
    public function revealSecret(Secret $secret): string
    {
        return DB::transaction(function () use ($secret) {
            $secret = Secret::where('id', $secret->getKey())->available()->lockForUpdate()->firstOrFail();

            $content = $secret->content ?? '';

            $secret->update([
                'content' => null,
                'revealed_at' => now(),
            ]);

            return $content;
        });
    }

    /**
     * Verify if the provided access token matches the access token.
     */
    public function checkAccessToken(Secret $secret, string $accessToken): bool
    {
        return Hash::check($accessToken, $secret->access_token);
    }

    /**
     * Verify if the provided passphrase matches the hashed passphrase.
     */
    public function checkPassphrase(Secret $secret, ?string $passphrase = null): bool
    {
        // Return true if the secret doesn't require a passphrase
        if ($secret->passphrase === null) {
            return true;
        }

        // Return false if a passphrase is not provided when the secret requires one
        if ($passphrase === null || $passphrase === '') {
            return false;
        }

        return Hash::check($passphrase, $secret->passphrase);
    }

    /**
     * Validate the secret is available, the access token is correct, and, if required, validate the secret's passphrase.
     *
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function validateAccessToAvailableSecret(Secret $secret, string $accessToken, ?string $passphrase = null): void
    {
        $this->validateAvailability(secret: $secret);
        $this->validateAccessToken(secret: $secret, accessToken: $accessToken);
        $this->validatePassphrase(secret: $secret, passphrase: $passphrase);
    }

    /**
     * Validate the secret is available.
     *
     * @throws ModelNotFoundException
     */
    public function validateAvailability(Secret $secret): void
    {
        if (! $secret->is_available) {
            throw new ModelNotFoundException;
        }
    }

    /**
     * Validate the access token for a secret is correct.
     *
     * @throws ModelNotFoundException
     */
    public function validateAccessToken(Secret $secret, string $accessToken): void
    {
        if (! $this->checkAccessToken(secret: $secret, accessToken: $accessToken)) {
            throw new ModelNotFoundException;
        }
    }

    /**
     * Validate the passphrase for a secret if secret is passphrase-protected.
     *
     * @throws ValidationException
     */
    public function validatePassphrase(Secret $secret, ?string $passphrase = null): void
    {
        if (! $secret->is_passphrase_protected) {
            return;
        }

        if (! $this->checkPassphrase(secret: $secret, passphrase: $passphrase)) {
            throw ValidationException::withMessages([
                'passphrase' => ['The passphrase is incorrect.'],
            ]);
        }
    }

    /**
     * Wipe the content of the secret from the database permanently.
     *
     * The Secret model is preserved so the creator can still view its status on the receipt page.
     */
    public function wipeContent(Secret $secret): bool
    {
        return $secret->update(['content' => null]);
    }
}
