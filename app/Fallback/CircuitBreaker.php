<?php

namespace App\Fallback;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;

class CircuitBreaker implements CircuitBreakerInterface
{
    /**
     * @var RateLimiter
     */
    private RateLimiter $rateLimiter;

    /**
     * CircuitBreaker constructor.
     * @param RateLimiter $rateLimiter
     */
    public function __construct(RateLimiter $rateLimiter)
    {

        $this->rateLimiter = $rateLimiter;
    }

    public function isAvailable(string $serviceIdentifier): bool
    {
        return $this->rateLimiter->tooManyAttempts($serviceIdentifier, config('tkw-mailer.config.threshold'));
    }

    public function registerFailedAttempt(string $serviceIdentifier): void
    {
        $this->rateLimiter->hit('mailjet', Carbon::now()->addMinutes(5));
    }
}
