<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $emailId;
    private string $serviceIdentifier;
    private string $responseId;

    /**
     * Create a new event instance.
     *
     * @param int $emailId
     * @param string $serviceIdentifier
     * @param string $responseId
     */
    public function __construct(int $emailId, string $serviceIdentifier, string $responseId)
    {

        $this->emailId = $emailId;
        $this->serviceIdentifier = $serviceIdentifier;
        $this->responseId = $responseId;
    }

    public function getEmailId()
    {
        return $this->emailId;
    }

    public function getServiceIdentifier()
    {
        return $this->serviceIdentifier;
    }

    public function getResponseId()
    {
        return $this->responseId;
    }

}
