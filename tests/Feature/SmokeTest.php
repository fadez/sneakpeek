<?php

test(':dataset route passes the smoke test', function (array $route) {
    $this->get($route['url'])->assertOk();
})->with('routes_web_public');

it('loads easter egg page successfully', function () {
    $this->get(route('who-is-alex-fadez'))->assertStatus(418);
});

test(':dataset route returns no-store cache header for API route', function (array $route) {
    $this->getJson($route['url'])
        ->assertOk()
        ->assertHeaderContains('Cache-Control', 'no-store');
})->with('routes_api_not_cached');
