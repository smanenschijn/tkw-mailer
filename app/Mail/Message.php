<?php

namespace App\Mail;

class Message
{
    /**
     * @var array $recipients
     */
    protected $recipients = [];

    /**
     * @var string $subject
     */
    protected $subject;


    /**
     * @var string $body
     */
    protected $body;

    public function __construct($messageData)
    {
        $this->recipients = $messageData['recipients'];
        $this->subject = $messageData['subject'];
        $this->body = $messageData['body'];
    }

    public function send()
    {

    }
}
