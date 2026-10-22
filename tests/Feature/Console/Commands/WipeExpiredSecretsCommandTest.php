<?php

declare(strict_types=1);

use App\Console\Commands\WipeExpiredSecretsCommand;
use App\Models\Secret;

it('wipes content only for expired secrets that still have content', function () {
    $secretWithContentExpired = Secret::factory()->expired()->createFresh();
    $secretWithContentAvailable = Secret::factory()->expiresIn(seconds: 3600)->createFresh();

    expect($secretWithContentExpired->content)->toBeTruthy();
    expect($secretWithContentAvailable->content)->toBeTruthy();

    $this->artisan(WipeExpiredSecretsCommand::class)->assertOk();

    expect($secretWithContentExpired->refresh()->content)->toBeNull();
    expect($secretWithContentAvailable->refresh()->content)->toBeTruthy();
});

it('does not wipe content for available secrets expiring now', function () {
    $this->freezeSecond();

    $content = 'Secret content.';

    $secret = Secret::factory()->expiresNow()->createFresh(['content' => $content]);

    $this->artisan(WipeExpiredSecretsCommand::class)->assertOk();

    expect($secret->refresh()->content)->toBe($content);

    $this->travel(1)->second();

    $this->artisan(WipeExpiredSecretsCommand::class)->assertOk();

    expect($secret->refresh()->content)->toBeNull();
});
