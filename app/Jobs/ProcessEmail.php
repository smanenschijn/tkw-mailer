<?php

namespace App\Jobs;

use App\Mail\MailerInterface;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Email
     */
    private $emailId;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Create a new job instance.
     *
     * @param int $emailId
     */
    public function __construct(int $emailId)
    {
        $this->emailId = $emailId;
    }

    /**
     * Execute the job.
     *
     * @param MailerInterface $mailer
     * @return void
     */
    public function handle(MailerInterface $mailer)
    {
        Log::info('Send Job!');
        $mailer->send($this->emailId);
    }
}
