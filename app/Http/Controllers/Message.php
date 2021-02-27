<?php

namespace App\Http\Controllers;


class Message extends Controller
{

    public function createMessage()
    {
        return response()->json(['success' => true]);
    }

}
