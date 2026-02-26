<?php

it('can create and reveal a secret without passphrase', function () {
    $content = 'Secret content.';

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

    // Check that the secret is no longer accessible and the user was redirected back to the home page
    $page->refresh()
        ->assertPathIs('/');

    $page->screenshot(filename: screenshot_name('3_secret_no_longer_available_user_redirected_to_home_page'));
});
