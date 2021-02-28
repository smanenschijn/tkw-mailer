<?php

namespace App\Http\Controllers;


use App\Jobs\ProcessEmail;
use App\Mail\Mailer;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Message extends Controller
{

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

            $email = Email::create($messageData);
            ProcessEmail::dispatch($email);

            return response()->json(['success' => true]);

        } catch (ValidationException $validationException) {
            return response()->json(['errors' => 'message doesn\'t have the correct format'], 500);
        } catch (\Exception $exception) {
            return response()->json(['error' => 'failed to create email message'], 500);
        }

    }

}
