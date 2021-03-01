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

    public function sendMessage(int $emailId)
    {
        try {

            $email = $this->getEmail($emailId);
            $threshold = config('tkw-mailer.config.threshold');

            if ($this->rateLimiter->tooManyAttempts('sendgrid', $threshold)) {
                throw new ServiceUnavailableException('Service SendGrid is currently unavailable because of too many failed attempts');
            }

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

            MessageSent::dispatch($emailId, 'SendGrid');

            return true;

        } catch (\HttpRequestException | HttpClientException | HttpResponseException $serviceUnavailableException) {

            $this->rateLimiter->hit('sendgrid', Carbon::now()->addMinutes(15));

            throw new ServiceUnavailableException($serviceUnavailableException->getMessage());

        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            $this->fallback();
        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['email' => $recipient];
        }, $recipients);
    }

    public function fallback(int $emailId)
    {
        throw new AllServicesFailedException();
    }
}
