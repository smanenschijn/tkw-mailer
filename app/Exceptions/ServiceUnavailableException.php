<?php

namespace App\Exceptions;

use Exception;

class ServiceUnavailableException extends Exception
{
    private $serviceIdentifier;

    public function __construct($message = "", $serviceIdentifier, $code = 0, Throwable $previous = null)
    {
        $this->serviceIdentifier = $serviceIdentifier;

        parent::__construct($message, $code, $previous);
    }

    public function getServiceIdentifier()
    {
        return $this->serviceIdentifier;
    }
}
