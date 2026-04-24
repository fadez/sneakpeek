<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\StatisticService;
use Illuminate\Broadcasting\Channel;

final class StatisticUpdated extends BroadcastableEvent
{
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
        return 'statistic.updated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return resolve(StatisticService::class)->getSnapshot();
    }
}
