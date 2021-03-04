<?php

namespace Tests\Unit;

use App\Fallback\CircuitBreakerInterface;
use Illuminate\Support\Str;
use Tests\TestCase;

class CircuitBreakerTest extends TestCase
{

    /**
     * @test
     */
    public function test_it_returns_unavailable_when_a_service_failed_too_many_times()
    {
        $circuitBreaker = resolve(CircuitBreakerInterface::class);
        $serviceIdentifier = Str::uuid();

        for($i=0;$i <= config('tkw-mailer.rate_limiter.threshold') + 1; $i++) {
            $circuitBreaker->registerFailedAttempt($serviceIdentifier);
        }

        $this->assertFalse($circuitBreaker->isAvailable($serviceIdentifier));
    }

    /**
     * @test
     */
    public function it_returns_available_when_a_service_fail_limit_is_not_reached()
    {
        $circuitBreaker = resolve(CircuitBreakerInterface::class);
        $serviceIdentifier = Str::uuid();

        for($i=1;$i <= config('tkw-mailer.rate_limiter.threshold') - 1; $i++) {
            $circuitBreaker->registerFailedAttempt($serviceIdentifier);
        }

        $this->assertTrue($circuitBreaker->isAvailable($serviceIdentifier));
    }

    /**
     * @test
     */
    public function test_it_releases_a_service_after_the_cool_down_period()
    {
        $circuitBreaker = resolve(CircuitBreakerInterface::class);
        $serviceIdentifier = Str::uuid();

        for($i=1;$i <= config('tkw-mailer.rate_limiter.threshold'); $i++) {
            $circuitBreaker->registerFailedAttempt($serviceIdentifier);
        }

        //$this->travel(15)->minutes();

        $this->assertTrue($circuitBreaker->isAvailable($serviceIdentifier));
    }
}
