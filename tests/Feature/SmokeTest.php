<?php

use App\Models\Secret;

it('loads home page successfully', function () {
    $this->get(route('home'))->assertOk();
});

it('loads easter egg page successfully', function () {
    $this->get(route('who-is-alex-fadez'))->assertStatus(418);
});

it('returns no-store cache header for API route', function (string $url) {
    $this->getJson($url)
        ->assertOk()
        ->assertHeaderContains('Cache-Control', 'no-store');
})->with([
    'api.secrets.receipt' => fn () => route('api.secrets.receipt', ['secret' => Secret::factory()->create()->key]),
    'api.secrets.show' => fn () => route('api.secrets.show', ['secretKey' => Secret::factory()->create()->secret_key]),
]);
