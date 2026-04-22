<?php

declare(strict_types=1);

namespace App\Enums;

enum StatisticKey: string
{
    case SecretsCreated = 'secrets_created';
    case SecretsRevealed = 'secrets_revealed';
    case SecretsExpired = 'secrets_expired';
    case SecretsBurned = 'secrets_burned';
}
