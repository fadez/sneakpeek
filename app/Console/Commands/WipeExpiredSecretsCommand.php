<?php

declare(strict_types=1);

namespace App\Console\Commands;

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
    public function handle(SecretService $secretService, StatisticService $statisticService): int
    {
        $secretService->wipeExpiredSecrets();

        $this->components->info('Wiped expired secrets.');

        return self::SUCCESS;
    }
}
