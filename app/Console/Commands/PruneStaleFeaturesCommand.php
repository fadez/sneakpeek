<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

#[Signature('pennant:prune')]
#[Description('Prune stale Pennant features')]
class PruneStaleFeaturesCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $canPrune = Config::string('pennant.default') === 'database' && Config::string('session.driver') === 'database';

        if (! $canPrune) {
            $this->components->error('The session driver and the default Pennant store must both be set to "database".');

            return self::FAILURE;
        }

        $sessionsTable = Config::string('session.table');
        $featuresTable = Config::string('pennant.stores.database.table');

        $deletedCount = DB::table($featuresTable)
            ->whereNotIn('scope', DB::table($sessionsTable)->select('id'))
            ->delete();

        $this->components->info('Deleted ' . $deletedCount . ' stale Pennant ' . str('feature')->plural($deletedCount) . '.');

        return self::SUCCESS;
    }
}
