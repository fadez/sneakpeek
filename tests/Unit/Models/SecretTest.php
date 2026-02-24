<?php

use App\Models\Secret;
use Illuminate\Support\Carbon;

test('toArray', function () {
    $secret = Secret::factory()->createFresh();

    expect(array_keys($secret->toArray()))->toBe([
        'id',
        'expires_at',
        'revealed_at',
        'created_at',
        'updated_at',
        'is_passphrase_protected',
        'is_expired',
        'is_revealed',
        'is_available',
    ]);

    expect($secret->toArray())->not->toHaveKeys([
        'content',
        'access_token',
        'passphrase',
    ]);
});

test('content', function () {
    $content = 'Secret content.';
    $secret = Secret::factory()->createFresh(['content' => $content]);

    // Let's imagine someone got access to our DB, we need to ensure that content is encrypted
    $rawContent = DB::table('secrets')->find($secret->getKey())->content;

    expect($secret->content)->toBe($content);
    expect($rawContent)->not->toBe($content);
});

test('access_token', function (string $driver) {
    config(['hashing.driver' => $driver]);

    $accessToken = 'secret_access_token';
    $secret = Secret::factory()->createFresh(['access_token' => $accessToken]);

    // Verify that passphrase is hashed
    expect(Hash::check($accessToken, $secret->access_token))->toBeTrue();

    // Let's imagine someone got access to our DB, we need to ensure that access token is not accessible
    $rawAccessToken = DB::table('secrets')->find($secret->getKey())->access_token;

    expect($rawAccessToken)->not->toBe($accessToken);

    // Ensure the access token is already hashed using the intended algorithm
    expect(Hash::needsRehash($rawAccessToken))->toBeFalse();
})->with(['bcrypt', 'argon', 'argon2id']);

test('passphrase', function (string $driver) {
    config(['hashing.driver' => $driver]);

    $passphrase = 'secret';
    $secret = Secret::factory()->passphraseProtected(passphrase: $passphrase)->createFresh();

    // Verify that passphrase is hashed
    expect(Hash::check($passphrase, $secret->passphrase))->toBeTrue();

    // Let's imagine someone got access to our DB, we need to ensure that passphrase is not accessible
    $rawPassphrase = DB::table('secrets')->find($secret->getKey())->passphrase;

    expect($rawPassphrase)->not->toBe($passphrase);

    // Ensure the passphrase is already hashed using the intended algorithm
    expect(Hash::needsRehash($rawPassphrase))->toBeFalse();
})->with(['bcrypt', 'argon', 'argon2id']);

test('revealed_at', function () {
    $secret = Secret::factory()->revealed()->createFresh();

    expect($secret->revealed_at)->toBeInstanceOf(Carbon::class);
});

test('expires_at', function () {
    $secret = Secret::factory()->expiresIn(60)->createFresh();

    expect($secret->expires_at)->toBeInstanceOf(Carbon::class);
});

test('scopeAvailable', function () {
    Secret::factory()->createFresh();

    expect(Secret::available()->count())->toBeOne();
});

test('scopeHasContent', function () {
    Secret::factory()->createFresh();

    expect(Secret::hasContent()->count())->toBeOne();
    expect(Secret::hasContent()->first()->content)->not->toBeNull();
});

test('scopeExpired', function () {
    Secret::factory()->expired()->createFresh();

    expect(Secret::expired()->count())->toBeOne();
});

test('scopeNotExpired', function () {
    $this->freezeSecond();

    Secret::factory()->expiresNow()->createFresh();

    expect(Secret::notExpired()->count())->toBeOne();
});

test('scopeUnrevealed', function () {
    Secret::factory()->createFresh();

    expect(Secret::unrevealed()->count())->toBeOne();
});

test('scopeToBeWiped', function () {
    Secret::factory()->createFresh();
    Secret::factory()->expired()->createFresh();
    Secret::factory()->expired()->wiped()->createFresh();

    expect(Secret::toBeWiped()->count())->toBeOne();
});

test('isAvailable', function () {
    $secretAvailable = Secret::factory()->createFresh();
    $secretRevealed = Secret::factory()->revealed()->createFresh();
    $secretExpiredWithContent = Secret::factory()->expired()->createFresh();
    $secretExpiredWiped = Secret::factory()->expired()->wiped()->createFresh();

    expect($secretAvailable->is_available)->toBeTrue();
    expect($secretRevealed->is_available)->toBeFalse();
    expect($secretExpiredWithContent->is_available)->toBeFalse();
    expect($secretExpiredWiped->is_available)->toBeFalse();
});

test('isRevealed', function () {
    $secret = Secret::factory()->revealed()->createFresh();

    expect($secret->is_revealed)->toBeTrue();
});

test('isExpired', function () {
    $secret = Secret::factory()->expired()->createFresh();

    expect($secret->is_expired)->toBeTrue();
});
