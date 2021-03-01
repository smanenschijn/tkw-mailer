<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Jobs\ProcessEmail;
use App\Repositories\EmailRepositoryInterface;

class SendMessageWithServices
{
    /**
     * Handle the event.
     *
     * @param MessageCreated $event
     * @return void
     */
    public function handle(MessageCreated $event)
    {
        ProcessEmail::dispatch($event->getEmailId());
    }
}
