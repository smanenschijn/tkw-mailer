<?php

namespace App\Mail;

use App\Exceptions\AllServicesFailedException;
use App\Exceptions\ServiceFailedException;
use App\Exceptions\ServiceUnavailableException;
use App\Mail\Services\ServiceInterface;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Log;

class Mailer
{
    /**
     * Sends a message with availability of several services in mind
     *
     * @param Email $email
     * @return string[]
     */
    public function send(Email $email)
    {
        $limiter = app(RateLimiter::class);
        $threshold = config('tkw-mailer.config.threshold');

        try {

            foreach (config('tkw-mailer.services') as $index => $service) {

                try {
                    if ($limiter->tooManyAttempts($index, $threshold)) {
                        throw new ServiceUnavailableException('Service %s is currently unavailable because of too many failed attempts',config(sprintf('tkw-mailer.services.%s.name', $index)));
                    }
                    /* @var ServiceInterface $mailService */
                    $serviceClass = config(sprintf('tkw-mailer.services.%s.class', $index));
                    $mailService = new $serviceClass;

                    return $mailService->sendMessage($email);

                } catch (ServiceFailedException $e) {
                    $limiter->hit($index, Carbon::now()->addMinutes(15));
                    Log::error($e->getMessage());
                } catch (ServiceUnavailableException $e) {
                    Log::info($e->getMessage());
                }
            }


            throw new AllServicesFailedException();

        } catch (AllServicesFailedException $e) {
            Log::error('All services unavailable at this time');
            return ['error' => 'All services unavailable at this time'];
        }
    }
}
