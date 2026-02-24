<?php

use App\Models\Secret;

it('wipes content only for expired secrets that still have content', function () {
    $secretWithContentExpired = Secret::factory()->expired()->createFresh();
    $secretWithContentAvailable = Secret::factory()->expiresIn(seconds: 3600)->createFresh();

    expect($secretWithContentExpired->content)->toBeTruthy();
    expect($secretWithContentAvailable->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertOk();

    expect($secretWithContentExpired->refresh()->content)->toBeNull();
    expect($secretWithContentAvailable->refresh()->content)->toBeTruthy();
});

it('does not wipe content for available secrets expiring now', function () {
    $this->freezeSecond();

    $secret = Secret::factory()->expiresNow()->createFresh();

    expect($secret->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertOk();

    expect($secret->refresh()->content)->toBeTruthy();

    $this->travel(1)->second();

    $this->artisan('secrets:wipe-expired')
        ->expectsOutput('Wiped 1 expired secret.')
        ->assertOk();

    expect($secret->refresh()->content)->toBeNull();
});
