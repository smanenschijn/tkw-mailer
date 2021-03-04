<?php

namespace App\Mail;

use App\Events\MessageSent;
use App\Exceptions\AllServicesFailedException;
use App\Exceptions\ServiceUnavailableException;
use App\Fallback\CircuitBreakerInterface;
use App\Mail\Services\ServiceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class Mailer implements MailerInterface
{
    /**
     * @var CircuitBreakerInterface
     */
    private CircuitBreakerInterface $circuitBreaker;

    /**
     * Mailer constructor.
     * @param CircuitBreakerInterface $circuitBreaker
     */
    public function __construct(CircuitBreakerInterface $circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
    }

    /**
     * Sends a message with availability of several services in mind
     *
     * @param int $emailId
     * @return string|void
     *
     * @throws AllServicesFailedException
     */
    public function send(int $emailId)
    {
        try {
            foreach ($this->getServices() as $serviceIdentifier) {

                if ($this->circuitBreaker->isAvailable($serviceIdentifier)) {

                    $mailService = $this->resolveService($serviceIdentifier);

                    Log::info($mailService->getServiceIdentifier());

                    $responseId = $mailService->sendMessage($emailId);

                    MessageSent::dispatch($emailId, $mailService->getServiceIdentifier(), $responseId);

                    return $responseId;
                }
            }

            throw new AllServicesFailedException('All services are currently unavailable');

        } catch (ServiceUnavailableException $serviceUnavailableException) {

            $this->circuitBreaker->registerFailedAttempt($serviceUnavailableException->getServiceIdentifier());
        } catch (AllServicesFailedException $allServicesFailedException) {

            Log::error($allServicesFailedException->getMessage());
            throw new AllServicesFailedException($allServicesFailedException->getMessage());
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function getServices(): array
    {
        return config('tkw-mailer.settings.order');
    }

    public function resolveService($identifier): ServiceInterface
    {
        return resolve(config(sprintf('tkw-mailer.services.%s.class', $identifier)));
    }

}
