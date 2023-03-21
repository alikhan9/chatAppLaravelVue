<?php

namespace App\Events;

use App\Models\PublicMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicMessageSent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $publicMessage;

    /**
     * Create a new event instance.
     *
     * @param $message
     */
    public function __construct(PublicMessage $message)
    {
        $this->publicMessage = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('chat-public-'. $this->publicMessage->to);
    }
}