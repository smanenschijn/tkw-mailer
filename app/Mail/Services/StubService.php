<?php

namespace App\Mail\Services;

use App\Exceptions\ServiceUnavailableException;
use App\Mail\Services\ServiceInterface;
use Illuminate\Support\Str;

class StubService implements ServiceInterface
{

    public function getServiceIdentifier(): string
    {
        return Str::uuid();
    }

    public function sendMessage(int $emailId) : string
    {
        if ($emailId === 1) {
            return Str::uuid();
        }

        throw new ServiceUnavailableException('Service unavailable', 'stub-service');

    }
}
