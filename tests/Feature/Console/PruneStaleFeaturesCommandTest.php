<?php

use App\Console\Commands\PruneStaleFeaturesCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

it('prunes stale Pennant features', function () {
    $sessionsTable = Config::string('session.table');
    $featuresTable = Config::string('pennant.stores.database.table');

    DB::table($sessionsTable)->insert(['id' => 'active-session', 'payload' => '', 'last_activity' => now()->timestamp]);
    DB::table($sessionsTable)->insert(['id' => 'stale-session', 'payload' => '', 'last_activity' => now()->timestamp]);

    DB::table($featuresTable)->insert(['name' => 'test-feature', 'scope' => 'active-session', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()]);
    DB::table($featuresTable)->insert(['name' => 'test-feature', 'scope' => 'stale-session', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()]);

    DB::table($sessionsTable)->where('id', 'stale-session')->delete();

    $this->artisan(PruneStaleFeaturesCommand::class)
        ->expectsOutputToContain('Deleted 1 stale Pennant feature.')
        ->assertOk();

    expect(DB::table($featuresTable)->where('scope', 'active-session')->exists())->toBeTrue();
    expect(DB::table($featuresTable)->where('scope', 'stale-session')->exists())->toBeFalse();
});

it('does nothing when there are no stale features', function () {
    $this->artisan(PruneStaleFeaturesCommand::class)
        ->expectsOutputToContain('Deleted 0 stale Pennant features.')
        ->assertOk();
});

it('fails when Pennant store is not database', function () {
    Config::set('pennant.default', 'array');

    $this->artisan(PruneStaleFeaturesCommand::class)
        ->expectsOutputToContain('The session driver and the default Pennant store must both be set to "database".')
        ->assertFailed();
});

it('fails when session driver is not database', function () {
    Config::set('session.driver', 'file');

    $this->artisan(PruneStaleFeaturesCommand::class)
        ->expectsOutputToContain('The session driver and the default Pennant store must both be set to "database".')
        ->assertFailed();
});
