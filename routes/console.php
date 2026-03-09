<?php

use App\Console\Commands\PruneStaleFeaturesCommand;
use App\Console\Commands\WipeExpiredSecretsCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->everyMinute()->withoutOverlapping();
Schedule::command(WipeExpiredSecretsCommand::class)->everyMinute()->withoutOverlapping();
Schedule::command(PruneStaleFeaturesCommand::class)->hourly()->withoutOverlapping();
