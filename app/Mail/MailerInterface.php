<?php

namespace app\Mail;

use App\Models\Email;

interface MailerInterface
{
    public function send(Email $email) : bool;
}
