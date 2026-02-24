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
use Illuminate\Support\Str;

class SecretController extends Controller
{
    public function __construct(protected SecretService $secretService) {}

    /**
     * Store a new secret.
     *
     * The access token is sent to user only once — here.
     *
     * The secret reveal URL places the access token after the "#" fragment for these security reasons:
     * - The hash fragment is never sent to the server.
     * - Prevents token logging in server, proxy, and analytics logs.
     * - Prevents accidental leakage via HTTP Referer headers.
     */
    public function store(StoreSecretRequest $request): SecretResource
    {
        $accessToken = Str::random(64);

        $secret = $this->secretService->createFromRequest(
            request: $request,
            accessToken: $accessToken
        );

        return (new SecretResource($secret))->additional(['secret' => ['access_token' => $accessToken]]);
    }

    /**
     * Get information about a secret.
     */
    public function show(Request $request, Secret $secret): SecretResource
    {
        // If access_token param is included in URL we'll also want to check that secret is available before revealing it
        if ($request->has('access_token')) {
            $this->secretService->validateAccessToken(
                secret: $secret,
                accessToken: $request->string('access_token'),
            );

            $this->secretService->validateAvailability(secret: $secret);
        }

        return new SecretResource($secret);
    }

    /**
     * Reveal a secret and wipe its content.
     * If the secret is passphrase-protected, the passphrase must be provided.
     */
    public function reveal(Request $request, Secret $secret): JsonResponse
    {
        $this->secretService->validateAccessToAvailableSecret(
            secret: $secret,
            accessToken: $request->string('access_token'),
            passphrase: $request->string('passphrase')
        );

        $content = $this->secretService->revealSecret($secret);

        return response()->json(['content' => $content]);
    }

    /**
     * Delete a secret.
     * Only secrets that have not been revealed can be deleted.
     */
    public function destroy(Request $request, Secret $secret): Response
    {
        $this->secretService->validateAccessToAvailableSecret(
            secret: $secret,
            accessToken: $request->string('access_token'),
            passphrase: $request->string('passphrase')
        );

        $secret->delete();

        return response()->noContent();
    }
}
