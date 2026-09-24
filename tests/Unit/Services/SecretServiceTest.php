<?php

declare(strict_types=1);

use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('passes passphrase check with an empty passphrase when secret is not passphrase-protected', function () {
    $secretService = resolve(SecretService::class);

    $secret = Secret::factory()->revealed()->createFresh();

    expect($secretService->checkPassphrase(secret: $secret))->toBeTrue();
});

it('passes passphrase check with any passphrase when secret is not passphrase-protected', function () {
    $secretService = resolve(SecretService::class);

    $secret = Secret::factory()->createFresh();

    expect($secretService->checkPassphrase(secret: $secret, passphrase: 'some passphrase'))->toBeTrue();
});

it('passes passphrase check for passphrase-protected secret only when the correct passphrase is provided', function () {
    $secretService = resolve(SecretService::class);

    $secret = Secret::factory()->passphraseProtected(passphrase: ' tricky § passphrase 😏 ')->createFresh();

    expect($secretService->checkPassphrase(secret: $secret))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: ''))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: 'incorrect passphrase'))->toBeFalse();
    expect($secretService->checkPassphrase(secret: $secret, passphrase: ' tricky § passphrase 😏 '))->toBeTrue();
});

it('wipes content of the secret but preserves the model', function () {
    $secretService = resolve(SecretService::class);

    $secret = Secret::factory()->createFresh();

    $secretService->wipeContent($secret);

    $secret->refresh();

    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('reveals secret content and wipes it', function () {
    $secretService = resolve(SecretService::class);

    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    $revealedContent = $secretService->revealSecret($secret);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('fails to reveal an expired secret', function () {
    $secretService = resolve(SecretService::class);

    $this->freezeSecond();

    $secret = Secret::factory()->expiresNow()->createFresh();

    $this->travel(1)->second();

    expect(fn () => $secretService->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);
});

it('throws when revealing a secret that has already been revealed', function () {
    $secretService = resolve(SecretService::class);

    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    $revealedContent = $secretService->revealSecret($secret);

    expect(fn () => $secretService->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});
