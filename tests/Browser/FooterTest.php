<?php

it('was built with love', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'Built with ❤️');
});

it('has author attribution', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', '@fadez');
});

it('has link to GitHub repository', function () {
    $page = visit('/');

    $page->assertSeeIn('@footer', 'View source code on GitHub');
});
