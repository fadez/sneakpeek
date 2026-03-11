<?php

namespace App\Console\Commands;

use App\Models\Secret;
use App\Models\Statistic;
use App\Services\SecretService;
use App\Services\StatisticService;
use Illuminate\Console\Command;

class WipeExpiredSecretsCommand extends Command
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
    public function handle(SecretService $secretService, StatisticService $statisticService): void
    {
        $wipedCount = 0;

        Secret::toBeWiped()->cursor()->each(function (Secret $secret) use ($secretService, &$wipedCount) {
            $secretService->wipeContent($secret);

            $wipedCount++;
        });

        $statisticService->incrementValue(Statistic::KEY_SECRETS_EXPIRED, $wipedCount);

        $this->components->info('Wiped ' . $wipedCount . ' expired ' . str('secret')->plural($wipedCount) . '.');
    }
}
