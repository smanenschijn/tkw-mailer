<?php

namespace App\Jobs;

use App\Mail\Mailer;
use app\Mail\MailerInterface;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Email
     */
    private $email;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Create a new job instance.
     *
     * @param Email $email
     * @param MailerInterface $mailer
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @param MailerInterface $mailer
     * @return void
     */
    public function handle(MailerInterface $mailer)
    {
        $mailer->send($this->email);
    }
}
