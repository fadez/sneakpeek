<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

abstract class BroadcastableEvent extends Event implements ShouldBroadcast
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
