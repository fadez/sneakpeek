<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\StatisticKey;
use App\Models\Secret;
use App\Services\SecretService;
use App\Services\StatisticService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('secrets:wipe-expired')]
#[Description('Wipe the content of expired secrets')]
final class WipeExpiredSecretsCommand extends Command
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

        $statisticService->incrementValue(StatisticKey::SecretsExpired, $wipedCount);

        $this->components->info('Wiped ' . $wipedCount . ' expired ' . str('secret')->plural($wipedCount) . '.');
    }
}
