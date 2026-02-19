<?php

use App\Models\Secret;

it('wipes content only for expired secrets that still have content', function () {
    $expiredSecretWithContent = Secret::factory()->expired()->create();
    $activeSecretWithContent = Secret::factory()->expiresIn(seconds: 3600)->create();
    $activeSecretThatNeverExpires = Secret::factory()->neverExpires()->create();

    expect($expiredSecretWithContent->content)->toBeTruthy();
    expect($activeSecretWithContent->content)->toBeTruthy();
    expect($activeSecretThatNeverExpires->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertOk();

    expect($expiredSecretWithContent->refresh()->content)->toBeNull();
    expect($activeSecretWithContent->refresh()->content)->toBeTruthy();
    expect($activeSecretThatNeverExpires->refresh()->content)->toBeTruthy();
});

it('does not wipe content for active secrets expiring now', function () {
    $this->freezeSecond();

    $secretExpiresNow = Secret::factory()->expiresNow()->create();

    expect($secretExpiresNow->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertOk();

    expect($secretExpiresNow->refresh()->content)->toBeTruthy();

    $this->travel(1)->second();

    $this->artisan('secrets:wipe-expired')
        ->expectsOutput('Wiped 1 expired secret.')
        ->assertOk();

    expect($secretExpiresNow->refresh()->content)->toBeNull();
});
