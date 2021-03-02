<?php

namespace App\Mail\Services;

use App\Events\MessageSent;
use App\Exceptions\AllServicesFailedException;
use App\Exceptions\ServiceFailedException;
use App\Exceptions\ServiceUnavailableException;
use App\Repositories\EmailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendGrid extends BaseService implements SendGridInterface {

    const SERVICE_IDENTIFIER='sendgrid';

    public function getServiceIdentifier(): string
    {
        return static::SERVICE_IDENTIFIER;
    }

    /**
     * @param int $emailId
     * @return string
     * @throws ServiceUnavailableException
     */
    public function sendMessage(int $emailId) : string
    {
        try {

            $email = $this->getEmail($emailId);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => config('tkw-mailer.services.sendgrid.token')
                ])
                ->post(config('tkw-mailer.services.sendgrid.url'), [
                    'personalizations' => [
                            ['to' => $this->formatRecipients($email->recipients)],
                        ],
                        'from' => ['email' => config('tkw-mailer.settings.email.from'), 'name' => config('tkw-mailer.settings.email.from')],
                        'subject' => $email->subject,
                        'content' => [[
                            'type' => 'text/html',
                            'value' => $email->body
                        ]]
                ])->throw();

            Log::info($response->header('X-Message-Id'));

            $responseId = $response->header('X-Message-Id');

            MessageSent::dispatch($emailId, $this->getServiceIdentifier(), $responseId);

            return $responseId;

        } catch (\HttpRequestException | HttpClientException | HttpResponseException $serviceUnavailableException) {

            throw new ServiceUnavailableException($serviceUnavailableException->getMessage(), $this->getServiceIdentifier());

        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['email' => $recipient];
        }, $recipients);
    }
}
