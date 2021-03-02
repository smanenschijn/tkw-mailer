<?php

namespace App\Mail;

use App\Events\MessageSent;
use App\Exceptions\AllServicesFailedException;
use App\Exceptions\ServiceFailedException;
use App\Exceptions\ServiceUnavailableException;
use App\Mail\Services\ServiceInterface;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;

class Mailer implements MailerInterface
{
    /**
     * @var ServiceInterface
     */
    private ServiceInterface $service;

    /**
     * Mailer constructor.
     * @param ServiceInterface $service
     */
    public function __construct(ServiceInterface $service)
    {

        $this->service = $service;
    }

    /**
     * Sends a message with availability of several services in mind
     *
     * @param int $emailId
     * @return boolean
     */
    public function send(int $emailId) : void
    {
        try {
            $this->service->sendMessage($emailId);
        } catch (AllServicesFailedException $allServicesFailedException) {

            throw new AllServicesFailedException($allServicesFailedException->getMessage());
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
