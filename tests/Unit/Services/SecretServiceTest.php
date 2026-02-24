<?php

use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('passes passphrase check with an empty passphrase when secret is not passphrase-protected', function () {
    $secret = Secret::factory()->revealed()->createFresh();

    $secretService = new SecretService;

    expect($secretService->checkPassphrase(secret: $secret, passphrase: null))->toBeTrue();
});

it('passes passphrase check with any passphrase when secret is not passphrase-protected', function () {
    $secret = Secret::factory()->createFresh();

    $secretService = new SecretService;

    expect($secretService->checkPassphrase(secret: $secret, passphrase: 'some passphrase'))->toBeTrue();
});

it('passes passphrase check for passphrase-protected secret only when the correct passphrase is provided', function () {
    $secret = Secret::factory()->passphraseProtected(passphrase: ' tricky!passphrase ')->createFresh();

    $secretService = new SecretService;

    expect($secretService->checkPassphrase(secret: $secret, passphrase: null))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: ''))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: 'incorrect passphrase'))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: ' tricky!passphrase '))->toBeTrue();
});

it('wipes content of the secret but preserves the model', function () {
    $secret = Secret::factory()->createFresh();

    (new SecretService)->wipeContent($secret);

    $secret->refresh();

    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('reveals secret content and wipes it', function () {
    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    $revealedContent = (new SecretService)->revealSecret($secret);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('reveals secret content and wipes it atomically under race conditions', function () {
    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    // We'll simulate two concurrent reveals using transactions with artificial delay
    $serviceA = new SecretService;
    $serviceB = new SecretService;

    // Simulate a slow transaction, where reveal locks the row and succeeds
    $revealedContent = DB::transaction(function () use ($serviceA, $secret) {
        $content = $serviceA->revealSecret($secret);

        // Artificial delay to keep the transaction open and mimic a race condition
        sleep(1);

        return $content;
    });

    // Second reveal should fail because the secret has been atomically marked as revealed
    expect(fn () => $serviceB->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('fails to reveal an expired secret', function () {
    $this->freezeSecond();

    $secret = Secret::factory()->expiresNow()->createFresh();

    $service = new SecretService;

    $this->travel(1)->second();

    expect(fn () => $service->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);
});
