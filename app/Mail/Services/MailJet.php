<?php

namespace app\Mail\Services;

use App\Exceptions\ServiceFailedException;
use App\Mail\Services\ServiceInterface;
use App\Models\Email;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MailJet implements ServiceInterface
{

    public function sendMessage(Email $email)
    {
        try {
            $response = Http::timeout(10)->withBasicAuth('bb8aec6901629b4a034002e3b35f4d6a', '00e7a70928e87e44852d1ee100e0b2df')
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
                        "HTMLPart" => $email->body,
                        "CustomID"=> "AppGettingStartedTest"
                    ]
                ]
            ])->throw();


            return json_decode($response->body(), true);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            throw new ServiceFailedException('Failed to send email with MailJet');
        }
    }

    private function formatRecipients($recipients = [])
    {
        return array_map(function ($recipient) {
            return ['Email' => $recipient, 'Name' => $recipient];
        }, $recipients);
    }
}
