<?php

declare(strict_types=1);

use App\Actions\GenerateSecureToken;

it('generates a token of the given length', function (int $length) {
    $token = new GenerateSecureToken()->handle(length: $length);

    expect($token)->toHaveLength($length);
})->with([1, 16, 64, 123]);

it('generates only alphanumeric characters', function () {
    $token = new GenerateSecureToken()->handle(length: 256);

    expect($token)->toMatch('/^[a-zA-Z0-9]+$/');
});

it('generates a different token on each call', function () {
    $generateSecureTokenAction = new GenerateSecureToken;

    $tokens = array_map(fn (): string => $generateSecureTokenAction->handle(length: 64), range(1, 20));

    expect(array_unique($tokens))->toHaveCount(20);
});

it('throws when given a non-positive length', function (int $length) {
    new GenerateSecureToken()->handle(length: $length);
})->with([0, -1, -64])->throws(InvalidArgumentException::class, 'Token length must be greater than zero.');
