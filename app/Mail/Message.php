<?php

namespace App\Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    public function __construct(array $messageData)
    {
        $validator = Validator::make($messageData, [
            'recipients' => 'required|array',
            'subject' => 'required',
            'body' => 'required'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->recipients = $messageData['recipients'];
        $this->subject = $messageData['subject'];
        $this->body = $messageData['body'];
    }

    public function send()
    {

    }
}
