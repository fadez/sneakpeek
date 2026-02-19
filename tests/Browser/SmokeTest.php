<?php

test(':dataset route passes the smoke test', function (array $route) {
    visit($route['url'])
        ->assertNoSmoke() // Ensure there are no thrown JavaScript errors and no console logs
        ->assertSeeAnythingIn('@app') // Verify the Vue app root element is present and mounted
        ->screenshot(filename: screenshot_name($route['name']));
})->with('routes_web_public');
