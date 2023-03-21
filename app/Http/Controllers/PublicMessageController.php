<?php

namespace App\Http\Controllers;

use App\Models\PublicMessage;

class PublicMessageController extends Controller
{
    public function store()
    {
        $privateMessage = PublicMessage::create([
            'from' => auth()->user()->id,
            'to' => request()->to,
            'message' => request()->message
        ]);

        event(new MessageSent($privateMessage));
        return $privateMessage;
    }
}