<?php

namespace App\Mail\Services;

use App\Mail\Message;

interface ServiceInterface
{
    public function sendMessage(Message $message);
}
