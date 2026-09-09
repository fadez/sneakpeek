<?php

declare(strict_types=1);

it('can create and reveal a secret without passphrase', function () {
    $content = 'Secret content without passphrase protection.';

    // Create the secret
    $page = visit('/')
        ->type('@secret-content-textarea', $content)
        ->pressAndWaitFor('@create-secret-btn', 0.2)
        ->assertPresent('@secret-link-input');

    // Get the URL that allows us to reveal the secret we just created
    $secretUrl = $page->value('@secret-link-input');

    $page->screenshot(filename: screenshot_name('1_secret_created'));

    // Reveal the secret
    $page->navigate($secretUrl)
        ->assertPresent('@reveal-secret-btn')
        ->pressAndWaitFor('@reveal-secret-btn', 0.2)
        ->assertValue('@secret-content-textarea', $content);

    $page->screenshot(filename: screenshot_name('2_secret_revealed'));

    // Check that the secret is no longer accessible
    $page->refresh()
        ->assertSee('This secret is nowhere to be found. Maybe it was deleted. Maybe it never existed. We recommend asking for a new one.');

    $page->screenshot(filename: screenshot_name('3_secret_no_longer_available'));
})->flaky();

it('can create and reveal a secret with passphrase', function () {
    $content = 'Secret content protected by passphrase.';
    $passphrase = 'Very secret passphrase.';

    // Create the secret
    $page = visit('/')
        ->type('@secret-content-textarea', $content)
        ->type('@passphrase-input', $passphrase)
        ->pressAndWaitFor('@create-secret-btn', 0.2)
        ->assertPresent('@secret-link-input');

    // Get the URL that allows us to reveal the secret we just created
    $secretUrl = $page->value('@secret-link-input');

    $page->screenshot(filename: screenshot_name('1_secret_created'));

    // Reveal the secret
    $page->navigate($secretUrl)
        ->assertPresent('@reveal-secret-btn')
        ->type('@passphrase-input', $passphrase)
        ->pressAndWaitFor('@reveal-secret-btn', 0.2)
        ->assertValue('@secret-content-textarea', $content);

    $page->screenshot(filename: screenshot_name('2_secret_revealed'));

    // Check that the secret is no longer accessible
    $page->refresh()
        ->assertSee('This secret is nowhere to be found. Maybe it was deleted. Maybe it never existed. We recommend asking for a new one.');

    $page->screenshot(filename: screenshot_name('3_secret_no_longer_available'));
})->flaky();

it('cannot see a secret link on receipt page after a page refresh', function () {
    $content = 'Secret content.';

    // Create the secret
    $page = visit('/')
        ->type('@secret-content-textarea', $content)
        ->pressAndWaitFor('@create-secret-btn', 0.2)
        ->assertPresent('@secret-link-input');

    $page->screenshot(filename: screenshot_name('1_secret_created'));

    // Refresh the page and check that the secret URL is no longer accessible
    $page->refresh()
        ->assertNotPresent('@secret-link-input');

    $page->screenshot(filename: screenshot_name('2_secret_url_no_longer_available'));
})->flaky();
