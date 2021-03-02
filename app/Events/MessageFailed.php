<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $emailId;

    /**
     * Create a new event instance.
     *
     * @param int $emailId
     */
    public function __construct(int $emailId)
    {
        //
        $this->emailId = $emailId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }
}
