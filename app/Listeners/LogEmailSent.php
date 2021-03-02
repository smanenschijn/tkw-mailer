<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogEmailSent
{
    /**
     * @var EmailRepositoryInterface
     */
    private EmailRepositoryInterface $emailRepository;

    /**
     * Create the event listener.
     *
     * @param EmailRepositoryInterface $emailRepository
     */
    public function __construct(EmailRepositoryInterface $emailRepository)
    {
        //
        $this->emailRepository = $emailRepository;
    }

    /**
     * Handle the event.
     *
     * @param MessageSent $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $email = $this->emailRepository->find($event->getEmailId());
        Log::info(sprintf('Message with subject \'%s\' was sent with mailservice \'%s\'', $email->subject, $event->getServiceIdentifier()));
    }
}
