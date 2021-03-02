<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateEmailStatus
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
        $this->emailRepository = $emailRepository;
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $this->emailRepository->update($event->getEmailId(), [
            'status' => 1,
            'sent_with_service' => $event->getServiceIdentifier(),
            'service_identifier' => $event->getResponseId()
        ]);
    }
}
