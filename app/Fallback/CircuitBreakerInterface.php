<?php

namespace App\Fallback;

interface CircuitBreakerInterface
{
    public function isAvailable(string $serviceIdentifier): bool;

    public function registerFailedAttempt(string $serviceIdentifier): void;
}
