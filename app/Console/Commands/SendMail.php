<?php

namespace App\Console\Commands;

use App\Events\MessageCreated;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SendMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tkw:mail:send {recipients} {subject} {body}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a email through a console command';
    /**
     * @var EmailRepositoryInterface
     */
    private EmailRepositoryInterface $emailRepository;

    /**
     * Create a new command instance.
     *
     * @param EmailRepositoryInterface $emailRepository
     */
    public function __construct(EmailRepositoryInterface $emailRepository)
    {
        parent::__construct();
        $this->emailRepository = $emailRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $messageData = [
                'recipients' => explode(',', $this->argument('recipients')),
                'subject' => $this->argument('subject'),
                'body' => $this->argument('body')
            ];

            $validator = Validator::make($messageData, [
                'recipients' => 'required|array',
                'subject' => 'required',
                'body' => 'required'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $email = $this->emailRepository->create($messageData);
            MessageCreated::dispatch($email->id);

            return 'succesfully created message';

        } catch (ValidationException $validationException) {
            foreach ($validationException->errors() as $attribute => $error) {
                $this->warn($error[0]);
            }

            $this->error('validation failed for message');

        } catch (\Exception $exception) {

            Log::info($exception->getMessage());

            $this->error('failed to create message');

            return 0;
        }

    }
}
