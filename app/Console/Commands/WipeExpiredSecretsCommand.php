<?php

namespace App\Console\Commands;

use App\Models\Secret;
use App\Models\Statistic;
use App\Services\SecretService;
use App\Services\StatisticService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('secrets:wipe-expired')]
#[Description('Wipe the content of expired secrets')]
class WipeExpiredSecretsCommand extends Command
{
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
