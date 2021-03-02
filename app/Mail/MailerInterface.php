<?php

namespace App\Mail;

use App\Models\Email;

interface MailerInterface
{
    public function send(int $email) : string;
}
