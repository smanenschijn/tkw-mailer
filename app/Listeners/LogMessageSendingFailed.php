<?php

namespace App\Listeners;

use App\Events\MessageFailed;
use Illuminate\Support\Facades\Log;

class LogMessageSendingFailed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MessageFailed $event
     * @return void
     */
    public function handle(MessageFailed $event)
    {
        Log::error(sprintf('Message with id %i could not be sent because of all services are unavailable', $event->getEmailId()));
    }
}
