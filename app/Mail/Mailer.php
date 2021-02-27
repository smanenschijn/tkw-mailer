<?php

namespace App\Mail;

use App\Exceptions\AllServicesFailedException;
use App\Exceptions\ServiceFailedException;
use Appp\Mail\Services\ServiceInterface;
use Illuminate\Support\Facades\Log;

class Mailer
{
    public function send(Message $message)
    {
        try {

            foreach (config('tkw-mailer.services') as $service) {

                try {

                    /**
                     * @var ServiceInterface $mailService
                     */
                    $serviceClass = config(sprintf('tkw-mailer.services.%s.class', $service));
                    $mailService = new $serviceClass;

                    return $mailService->sendMessage($message);

                } catch (ServiceFailedException $e) {
                    Log::error(sprintf('failed to send mail through %s', config(sprintf('tkw-mailer.services.%s.class', $service))));
                }

            }

            throw new AllServicesFailedException();

        } catch (AllServicesFailedException $e) {
            Log::error('All services unavailable at this time');
        }
    }
}
