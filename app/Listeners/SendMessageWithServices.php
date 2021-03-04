<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Jobs\ProcessEmail;

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
