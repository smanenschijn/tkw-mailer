<?php

namespace App\Mail\Services;

use App\Models\Email;

interface ServiceInterface
{
    public function sendMessage(Email $email);
}
