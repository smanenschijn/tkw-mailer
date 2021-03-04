<?php

namespace App\Mail;

use App\Mail\Services\ServiceInterface;
use App\Mail\Services\StubService;

class StubMailer extends Mailer implements MailerInterface
{
    public function resolveService($identifier): ServiceInterface
    {
        return new StubService();
    }

    public function getServices(): array
    {
        return ['stub-service'];
    }
}
