<?php

declare(strict_types=1);

use App\Models\Secret;
use App\Services\SecretService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->secretService = resolve(SecretService::class);
});

it('passes passphrase check with an empty passphrase when secret is not passphrase-protected', function () {
    $secret = Secret::factory()->revealed()->createFresh();

    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: null))->toBeTrue();
});

it('passes passphrase check with any passphrase when secret is not passphrase-protected', function () {
    $secret = Secret::factory()->createFresh();

    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: 'some passphrase'))->toBeTrue();
});

it('passes passphrase check for passphrase-protected secret only when the correct passphrase is provided', function () {
    $secret = Secret::factory()->passphraseProtected(passphrase: ' tricky § passphrase 😏 ')->createFresh();

    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: null))->toBeFalse();
    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: ''))->toBeFalse();
    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: 'incorrect passphrase'))->toBeFalse();
    expect($this->secretService->checkPassphrase(secret: $secret, passphrase: ' tricky § passphrase 😏 '))->toBeTrue();
});

it('wipes content of the secret but preserves the model', function () {
    $secret = Secret::factory()->createFresh();

    $this->secretService->wipeContent($secret);

    $secret->refresh();

    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('reveals secret content and wipes it', function () {
    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    $revealedContent = $this->secretService->revealSecret($secret);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});

it('fails to reveal an expired secret', function () {
    $this->freezeSecond();

    $secret = Secret::factory()->expiresNow()->createFresh();

    $this->travel(1)->second();

    expect(fn () => $this->secretService->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);
});

it('throws when revealing a secret that has already been revealed', function () {
    $content = 'Secret content.';

    $secret = Secret::factory()->createFresh(['content' => $content]);

    $revealedContent = $this->secretService->revealSecret($secret);

    expect(fn () => $this->secretService->revealSecret($secret))
        ->toThrow(ModelNotFoundException::class);

    $secret->refresh();

    expect($revealedContent)->toBe($content);
    expect($secret->exists)->toBeTrue();
    expect($secret->content)->toBeNull();
});
