<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldRescue;

abstract class BroadcastableEvent extends Event implements ShouldBroadcast, ShouldRescue
{
    /**
     * The channels the event should broadcast on.
     */
    abstract public function broadcastOn(): array;

    /**
     * The event's broadcast name.
     */
    abstract public function broadcastAs(): string;
}
