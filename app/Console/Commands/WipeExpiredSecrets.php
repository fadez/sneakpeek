<?php

namespace App\Console\Commands;

use App\Models\Secret;
use Illuminate\Console\Command;

class WipeExpiredSecrets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secrets:wipe-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipe the content of expired secrets';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $wipedCount = 0;

        Secret::toBeWiped()->cursor()->each(function ($secret) use (&$wipedCount) {
            $secret->wipeContent();

            $wipedCount++;
        });

        $this->info("Wiped {$wipedCount} expired secrets.");
    }
}
