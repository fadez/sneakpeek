<?php

namespace App\Services;

use App\Http\Requests\StoreSecretRequest;
use App\Models\Secret;
use Illuminate\Http\Request;
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
    public function createFromRequest(StoreSecretRequest $request): Secret
    {
        return Secret::create([
            'key' => Str::random(64),
            'secret_key' => Str::random(64),
            'content' => $request->input('content'),
            'passphrase' => $request->input('passphrase'),
            'expires_at' => $request->filled('ttl') ? now()->addSeconds($request->integer('ttl')) : null,
        ]);
    }

    /**
     * Mark the secret as revealed and wipe the content of the secret from the database permanently.
     */
    public function markAsRevealed(Secret $secret): void
    {
        if ($secret->is_revealed) {
            return;
        }

        $secret->content = null;
        $secret->revealed_at = now();
        $secret->save();
    }

    /**
     * Find an active secret by its secret key.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findActive(string $secretKey): Secret
    {
        return Secret::where('secret_key', $secretKey)->active()->firstOrFail();
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
     * Validate the passphrase for a secret if secret is passphrase-protected.
     *
     * @throws ValidationException
     */
    public function validatePassphrase(Request $request, Secret $secret): void
    {
        if (! $secret->is_passphrase_protected) {
            return;
        }

        if (! $this->checkPassphrase(secret: $secret, passphrase: $request->string('passphrase'))) {
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
