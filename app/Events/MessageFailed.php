<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
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
