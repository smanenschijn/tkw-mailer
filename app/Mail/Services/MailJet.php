<?php

namespace App\Mail\Services;

use App\Events\MessageSent;
use App\Exceptions\ServiceFailedException;
use App\Exceptions\ServiceUnavailableException;
use App\Repositories\EmailRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailJet extends BaseService implements ServiceInterface
{
    /**
     * @var SendGridInterface
     */
    private SendGridInterface $sendGrid;

    /**
     * MailJet constructor.
     * @param SendGridInterface $sendGrid
     * @param EmailRepositoryInterface $emailRepository
     */
    public function __construct(SendGridInterface $sendGrid, RateLimiter $rateLimiter, EmailRepositoryInterface $emailRepository)
    {
        $this->sendGrid = $sendGrid;

        parent::__construct($rateLimiter, $emailRepository);
    }


    public function sendMessage(int $emailId)
    {
        try {
            $email = $this->getEmail($emailId);
            $threshold = config('tkw-mailer.config.threshold');

            if ($this->rateLimiter->tooManyAttempts('mailjet', $threshold)) {
                throw new ServiceUnavailableException('Service MailJet is currently unavailable because of too many failed attempts');
            }

            Http::timeout(10)->withBasicAuth('1' . config('tkw-mailer.services.mailjet.username'), config('tkw-mailer.services.mailjet.password'))
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

            MessageSent::dispatch($emailId, 'MailJet');

            return true;

        } catch (ServiceUnavailableException $serviceUnavailableException) {

            $this->rateLimiter->hit('mailjet', Carbon::now()->addMinutes(15));

            throw new ServiceUnavailableException($serviceUnavailableException->getMessage());

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            $this->fallback($emailId);
        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['Email' => $recipient, 'Name' => $recipient];
        }, $recipients);
    }

    public function fallback(int $emailId) : bool
    {
        Log::info('fallback');
        return $this->sendGrid->sendMessage($emailId);
    }
}
