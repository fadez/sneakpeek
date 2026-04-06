<?php

declare(strict_types=1);

it('is true that true equals true when true is true', function () {
    expect(true)->toBeTrue();
})->group('humor');
