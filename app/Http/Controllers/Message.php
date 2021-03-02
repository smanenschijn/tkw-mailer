<?php

namespace App\Http\Controllers;


use App\Events\MessageCreated;
use App\Repositories\EmailRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Message extends Controller
{
    /**
     * @var EmailRepositoryInterface
     */
    private EmailRepositoryInterface $emailRepository;

    public function __construct(EmailRepositoryInterface $emailRepository)
    {

        $this->emailRepository = $emailRepository;
    }


    public function createMessage(Request $request)
    {
        try {
            $messageData = json_decode($request->getContent(), true);

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

            return response()->json(['success' => true]);

        } catch (ValidationException $validationException) {

            Log::error($validationException->getMessage());

            return response()->json(['errors' => 'message doesn\'t have the correct format'], 500);

        } catch (Exception $exception) {

            Log::error($exception->getMessage());

            return response()->json(['error' => 'failed to create email message'], 500);
        }

    }

}
