<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Message extends Controller
{

    public function createMessage(Request $request)
    {
        try {
            $messageData = json_decode($request->getContent(), true);

            $message = new \App\Mail\Message($messageData);

            return response()->json(['success' => true]);

        } catch (ValidationException $validationException) {
            return response()->json(['errors' => $validator->errors()], 500);
        }

    }

}
