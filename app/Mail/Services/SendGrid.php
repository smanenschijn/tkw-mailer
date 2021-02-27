<?php

namespace App\Mail\Services;

use App\Exceptions\ServiceFailedException;
use App\Mail\Message;
use Appp\Mail\Services\ServiceInterface;
use Illuminate\Support\Facades\Http;

class SendGrid implements ServiceInterface {


    public function sendMessage(Message $message, string $recipient)
    {
        try {

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => config('tkw-mailer.services.sendgrid.url')
                ])
                ->post(config('tkw-mailer.services.sendgrid.url'), [
                    'personalizations' => [
                        'to' => ['email' => $recipient],
                        'from' => ['email' => config('tkw-mailer.settings.email.from')],
                        'subject' => $message->getSubject(),
                        'content' => [
                            'type' => 'text/html',
                            'value' => $message->getBody()
                        ]
                    ]
                ]);

            return json_decode($response->body());

        } catch (\Exception $exception) {
            throw new ServiceFailedException();
        }
    }
}
