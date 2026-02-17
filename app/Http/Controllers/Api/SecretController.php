<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSecretRequest;
use App\Http\Resources\SecretResource;
use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecretController extends Controller
{
    public function __construct(protected SecretService $secretService) {}

    /**
     * Store a new secret.
     */
    public function store(StoreSecretRequest $request): SecretResource
    {
        return new SecretResource($this->secretService->createFromRequest($request));
    }

    /**
     * Get a receipt for a secret.
     */
    public function receipt(Secret $secret): SecretResource
    {
        return new SecretResource($secret);
    }

    /**
     * Get general information about a secret.
     * A passphrase is not required for this route.
     */
    public function show(string $secretKey): SecretResource
    {
        $secret = $this->secretService->findActive(secretKey: $secretKey);

        return new SecretResource($secret);
    }

    /**
     * Reveal a secret and wipe its content.
     * If the secret is passphrase-protected, the passphrase must be provided.
     */
    public function reveal(Request $request, string $secretKey): JsonResponse
    {
        $secret = $this->secretService->findActive(secretKey: $secretKey);

        $this->secretService->validatePassphrase($request, $secret);

        $content = $secret->content;

        $this->secretService->markAsRevealed($secret);

        return response()->json(['content' => $content]);
    }

    /**
     * Delete a secret.
     * Only secrets that have not been revealed can be deleted.
     */
    public function destroy(Request $request, string $secretKey): Response
    {
        $secret = $this->secretService->findActive(secretKey: $secretKey);

        $this->secretService->validatePassphrase($request, $secret);

        $secret->delete();

        return response()->noContent();
    }
}
