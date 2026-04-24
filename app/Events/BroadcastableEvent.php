<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class BroadcastableEvent extends Event implements ShouldBroadcast
{
    abstract public function broadcastOn(): array;
}
