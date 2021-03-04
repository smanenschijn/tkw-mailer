<?php

namespace App\Mail;

interface MailerInterface
{
    public function send(int $email);
}
