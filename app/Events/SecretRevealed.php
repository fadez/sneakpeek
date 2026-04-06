<?php

declare(strict_types=1);

namespace App\Events;

use App\Http\Resources\SecretResource;
use App\Models\Secret;
use Illuminate\Broadcasting\Channel;

class SecretRevealed extends Event
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public Secret $secret
    ) {}

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

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'secret' => (new SecretResource($this->secret)),
        ];
    }
}
