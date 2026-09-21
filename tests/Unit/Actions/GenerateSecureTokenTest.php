<?php

declare(strict_types=1);

use App\Actions\GenerateSecureToken;
use Tests\TestCase;

beforeEach(function () {
    /** @var TestCase $this */
    $this->generateSecureToken = resolve(GenerateSecureToken::class);
});

it('generates a token of the given length', function () {
    $token = $this->generateSecureToken->handle(length: 123);

    expect($token)->toHaveLength(123);
});

it('generates only URL-safe characters', function () {
    $token = $this->generateSecureToken->handle(length: 64);

    expect($token)->toMatch('/^[a-zA-Z0-9]+$/');
});

it('generates a different token on each call', function () {
    $tokens = collect(range(1, 20))
        ->map(fn () => $this->generateSecureToken->handle(length: 64))
        ->unique();

    expect($tokens->count())->toBe(20);
});

it('throws when given a non-positive length', function (int $length) {
    $this->generateSecureToken->handle(length: $length);
})->with([0, -1, -64])->throws(InvalidArgumentException::class, 'Token length must be greater than zero.');
