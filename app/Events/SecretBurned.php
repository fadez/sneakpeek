<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;

class SecretBurned extends Event
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $secretId
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('secrets.' . $this->secretId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'secret.burned';
    }
}
