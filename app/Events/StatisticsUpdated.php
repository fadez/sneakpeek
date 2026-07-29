<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;

final class StatisticsUpdated extends BroadcastableEvent
{
    /**
     * Create a new event instance.
     *
     * @param  array<string, int>  $statistics
     */
    public function __construct(
        public readonly array $statistics,
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'statistics.updated';
    }
}
