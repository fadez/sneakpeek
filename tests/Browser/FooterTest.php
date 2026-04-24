<?php

declare(strict_types=1);

it('shows copyright symbol', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', '©');
})->flaky();

it('shows built with love in Ukraine message', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'Built with');
})->flaky();

it('shows author attribution', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'Alex Fadez');
})->flaky();

it('shows link to GitHub repository', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'GitHub');
})->flaky();
