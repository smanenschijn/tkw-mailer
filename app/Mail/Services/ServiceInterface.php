<?php

namespace App\Mail\Services;

interface ServiceInterface
{
    public function getServiceIdentifier() : string;

    public function sendMessage(int $emailId) : string;

    public function fallback(int $emailId);
}
