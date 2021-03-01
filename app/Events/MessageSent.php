<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $emailId;
    private string $serviceName;

    /**
     * Create a new event instance.
     *
     * @param int $emailId
     * @param string $serviceName
     */
    public function __construct(int $emailId, string $serviceName)
    {

        $this->emailId = $emailId;
        $this->serviceName = $serviceName;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

}
