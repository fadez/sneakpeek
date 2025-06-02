<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecretRequest;
use App\Http\Resources\SecretResource;
use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    public function __construct(protected SecretService $secretService) {}

    /**
     * Create a new secret.
     */
    public function store(StoreSecretRequest $request)
    {
        return new SecretResource($this->secretService->createFromRequest($request));
    }

    /**
     * Get a receipt for a secret.
     */
    public function receipt(Secret $secret)
    {
        return new SecretResource($secret);
    }

    /**
     * Show general information about a secret.
     * Passphrase is not required for this route.
     */
    public function show(string $secretKey)
    {
        $secret = $this->secretService->findAvailableSecretBySecretKey($secretKey);

        return new SecretResource($secret);
    }

    /**
     * Reveal a secret and wipe its content.
     * If the secret is passphrase protected, the passphrase must be provided.
     */
    public function reveal(Request $request, string $secretKey)
    {
        $secret = $this->secretService->findAvailableSecretBySecretKey($secretKey);

        $this->secretService->validatePassphrase($request, $secret);

        $content = $secret->content;

        $this->secretService->markAsRevealed($secret);

        return response()->json(['content' => $content]);
    }

    /**
     * Delete a secret.
     * Only secrets that have not been revealed can be deleted.
     */
    public function destroy(Request $request, string $secretKey)
    {
        $secret = $this->secretService->findAvailableSecretBySecretKey($secretKey);

        $this->secretService->validatePassphrase($request, $secret);

        $secret->delete();

        return response()->json(['message' => 'Secret has been deleted.']);
    }
}
