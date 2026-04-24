<?php

declare(strict_types=1);

use App\Console\Commands\PruneStaleFeaturesCommand;
use App\Console\Commands\WipeExpiredSecretsCommand;
use Illuminate\Database\Console\PruneCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(PruneCommand::class)->everyMinute()->withoutOverlapping();
Schedule::command(WipeExpiredSecretsCommand::class)->everyMinute()->withoutOverlapping();
Schedule::command(PruneStaleFeaturesCommand::class)->hourly()->withoutOverlapping();
