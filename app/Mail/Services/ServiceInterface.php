<?php

namespace Appp\Mail\Services;

use App\Mail\Message;

interface ServiceInterface
{
    public function sendMessage(Message $message, string $recipient);
}
