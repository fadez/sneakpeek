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
        'is_active',
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

    // Let's imagine someone got access to our DB,
    // we need to ensure that secret content is, indeed, secret
    $rawContent = DB::table('secrets')->find($secret->getKey())->content;

    expect($secret->content)->toBe($content);
    expect($rawContent)->not->toBe($content);
});

test('passphrase', function (string $driver) {
    config(['hashing.driver' => $driver]);

    $passphrase = 'secret';
    $secret = Secret::factory()->passphraseProtected(passphrase: $passphrase)->createFresh();

    // Verify that passphrase is hashed
    expect(Hash::check($passphrase, $secret->passphrase))->toBeTrue();

    // Let's imagine someone got access to our DB,
    // we need to ensure that passphrase is not accessible
    $rawPassphrase = DB::table('secrets')->find($secret->getKey())->passphrase;

    expect($rawPassphrase)->not->toBe($passphrase);

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

test('scopeActive', function () {
    Secret::factory()->createFresh();

    expect(Secret::active()->count())->toBeOne();
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

test('isActive', function () {
    $active = Secret::factory()->createFresh();
    $revealed = Secret::factory()->revealed()->createFresh();
    $expiredWithContent = Secret::factory()->expired()->createFresh();
    $expiredWiped = Secret::factory()->expired()->wiped()->createFresh();

    expect($active->is_active)->toBeTrue();
    expect($revealed->is_active)->toBeFalse();
    expect($expiredWithContent->is_active)->toBeFalse();
    expect($expiredWiped->is_active)->toBeFalse();
});

test('isRevealed', function () {
    $secret = Secret::factory()->revealed()->createFresh();

    expect($secret->is_revealed)->toBeTrue();
});

test('isExpired', function () {
    $secret = Secret::factory()->expired()->createFresh();

    expect($secret->is_expired)->toBeTrue();
});
