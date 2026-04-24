<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Secret;
use Illuminate\Broadcasting\Channel;

final class SecretRevealed extends BroadcastableEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Secret $secret
    ) {
        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('secrets.' . $this->secret->id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'secret.revealed';
    }
}
