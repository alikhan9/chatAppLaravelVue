<?php

namespace App\Http\Controllers;

use App\Events\PublicMessageSent;
use App\Models\PublicMessage;

class PublicMessageController extends Controller
{
    public function store()
    {
        $publicMessage = PublicMessage::create([
            'from' => auth()->user()->id,
            'to' => request()->to,
            'message' => request()->message
        ]);

        broadcast(new PublicMessageSent($publicMessage))->toOthers();
        $user = collect(['user' => ['name'=> auth()->user()->name]]);
        $merged = $user->merge($publicMessage);
        $merged->all();
        return $merged;
    }
}
