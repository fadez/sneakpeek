<?php

use App\Models\Secret;
use App\Services\SecretService;

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
