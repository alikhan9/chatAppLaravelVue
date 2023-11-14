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

        $privateMessage->created_at_human = $privateMessage->created_at->diffForHumans();

        event(new MessageSent($privateMessage));
        return $privateMessage;
    }

}
