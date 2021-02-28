<?php

namespace App\Mail\Services;

use App\Exceptions\ServiceFailedException;
use App\Mail\Services\ServiceInterface;
use App\Models\Email;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendGrid implements ServiceInterface {


    public function sendMessage(Email $email)
    {
        try {

            $response = Http::timeout(60)
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

            return json_decode($response->body(), true);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ServiceFailedException('Failed to send email with Sendgrid');
        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['email' => $recipient];
        }, $recipients);
    }
}
