<?php

use App\Console\Commands\WipeExpiredSecrets;
use Illuminate\Support\Facades\Schedule;

Schedule::command(WipeExpiredSecrets::class)->everyMinute()->withoutOverlapping();
Schedule::command('model:prune')->everyMinute()->withoutOverlapping();
