<?php

use App\Console\Commands\WipeExpiredSecrets;
use Illuminate\Support\Facades\Schedule;

Schedule::command(WipeExpiredSecrets::class)->everyMinute()->withoutOverlapping();
