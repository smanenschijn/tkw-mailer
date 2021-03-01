<?php

namespace App\Mail\Services;

interface ServiceInterface
{
    public function sendMessage(int $emailId);

    public function fallback(int $emailId);
}
