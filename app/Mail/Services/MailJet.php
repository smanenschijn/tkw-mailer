<?php

namespace App\Mail\Services;

use App\Events\MessageSent;
use App\Exceptions\ServiceUnavailableException;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailJet extends BaseService implements ServiceInterface
{

    const SERVICE_IDENTIFIER = 'mailjet';

    /**
     * @var SendGridInterface
     */
    private SendGridInterface $sendGrid;

    /**
     * MailJet constructor.
     * @param SendGridInterface $sendGrid
     * @param RateLimiter $rateLimiter
     * @param EmailRepositoryInterface $emailRepository
     */
    public function __construct(SendGridInterface $sendGrid, RateLimiter $rateLimiter, EmailRepositoryInterface $emailRepository)
    {
        $this->sendGrid = $sendGrid;

        parent::__construct($rateLimiter, $emailRepository);
    }

    /**
     * @param int $emailId
     * @return string
     * @throws ServiceUnavailableException
     */
    public function sendMessage(int $emailId): string
    {
        try {
            $email = $this->getEmail($emailId);
            $response = Http::timeout(10)->withBasicAuth(config('tkw-mailer.services.mailjet.username'), config('tkw-mailer.services.mailjet.password'))
                ->asJson()
                ->post(config('tkw-mailer.services.mailjet.url'), [
                    'Messages' => [
                        [
                            'From' => [
                                'Email' => config('tkw-mailer.settings.email.from'),
                                'Name' => config('tkw-mailer.settings.email.name')
                            ],
                            "To" => $this->formatRecipients($email->recipients),
                            "Subject" => $email->subject,
                            "TextPart" => $email->body,
                            "HTMLPart" => $email->body
                        ]
                    ]
                ])->throw();

            return json_decode($response->body(), true)['Messages'][0]['To'][0]['MessageID'];

        } catch (HttpClientException | HttpResponseException $serviceUnavailableException) {
            Log::info('throw unavailable exception');

            throw new ServiceUnavailableException($serviceUnavailableException->getMessage(), $this->getServiceIdentifier());

        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['Email' => $recipient, 'Name' => $recipient];
        }, $recipients);
    }

    public function getServiceIdentifier(): string
    {
        return static::SERVICE_IDENTIFIER;
    }

}
