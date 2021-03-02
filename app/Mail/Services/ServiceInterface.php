<?php

namespace App\Mail\Services;

use App\Exceptions\ServiceUnavailableException;

interface ServiceInterface
{
    public function getServiceIdentifier() : string;

    /**
     * @param int $emailId
     * @return string
     * @throws ServiceUnavailableException
     */
    public function sendMessage(int $emailId) : string;

}
