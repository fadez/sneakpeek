<?php

declare(strict_types=1);

it('shows copyright symbol', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', '©');
});

it('shows built with love in Ukraine message', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'Built with');
});

it('shows author attribution', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', '@fadez');
});

it('shows link to GitHub repository', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'GitHub');
});
