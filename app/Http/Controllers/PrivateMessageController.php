<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\PrivateMessage;

class PrivateMessageController extends Controller
{
    public function store()
    {
        $privateMessage = PrivateMessage::create([
            'from' => auth()->user()->id,
            'to' => request()->to,
            'message' => request()->message
        ]);

        event(new MessageSent($privateMessage));
        return $privateMessage;
    }

}
