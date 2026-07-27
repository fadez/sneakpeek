<?php

declare(strict_types=1);

namespace Tests;

use App\Services\SecretService;
use App\Services\StatisticService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Pest\Browser\Api\ArrayablePendingAwaitablePage;
use Pest\Browser\Api\PendingAwaitablePage;

abstract class TestCase extends BaseTestCase
{
    public ArrayablePendingAwaitablePage|PendingAwaitablePage|null $page = null;

    public ?SecretService $secretService = null;

    public ?StatisticService $statisticService = null;
}
