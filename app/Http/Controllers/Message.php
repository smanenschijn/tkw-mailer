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

            $validator = Validator::make($messageData, [
                'recipients' => 'required|array',
                'subject' => 'required',
                'body' => 'required'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            return response()->json(['success' => true]);

        } catch (ValidationException $validationException) {
            return response()->json(['errors' => $validator->errors()], 500);
        }

    }

}
