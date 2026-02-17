<?php

use App\Models\Secret;

it('wipes content only for expired secrets that still have content', function () {
    $expiredSecretWithContent = Secret::factory()->expired()->create();
    $activeSecretWithContent = Secret::factory()->expiresIn(seconds: 3600)->create();
    $activeSecretThatNeverExpires = Secret::factory()->neverExpires()->create();

    expect($expiredSecretWithContent->content)->toBeTruthy();
    expect($activeSecretWithContent->content)->toBeTruthy();
    expect($activeSecretThatNeverExpires->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertExitCode(0);

    expect($expiredSecretWithContent->refresh()->content)->toBeNull();
    expect($activeSecretWithContent->refresh()->content)->toBeTruthy();
    expect($activeSecretThatNeverExpires->refresh()->content)->toBeTruthy();
});

it('does not wipe content for active secrets expiring now', function () {
    $secretExpiresNow = Secret::factory()->expiresNow()->create();

    expect($secretExpiresNow->content)->toBeTruthy();

    $this->artisan('secrets:wipe-expired')->assertExitCode(0);

    expect($secretExpiresNow->refresh()->content)->toBeTruthy();
});
