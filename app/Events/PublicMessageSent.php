<?php

namespace App\Events;

use App\Models\PublicMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicMessageSent implements ShouldBroadcast
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
     * @return PrivateChannel
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat-public-'. $this->publicMessage->to);
    }
}
