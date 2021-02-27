<?php

namespace App\Http\Controllers;


use App\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Message extends Controller
{

    public function createMessage(Request $request)
    {
        try {
            $messageData = json_decode($request->getContent(), true);
            $mailer = new Mailer();
            $response = $mailer->send(new \App\Mail\Message($messageData));

            return response()->json(['success' => true, 'response' => $response]);

        } catch (ValidationException $validationException) {
            return response()->json(['errors' => 'incomplete data'], 500);
        }

    }

}
