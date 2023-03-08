<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search()
    {
        return Inertia::render(
            'Search',
            [
            'users' => User::where('name', 'like', '%'. request()->search . '%')
            ->where('id', '!=', auth()->user()->id)
            ->whereNotIn(
                'id',
                function ($query) {
                    $query->select('friend_id')
                    ->from('friends')
                    ->where('user_id', '=', auth()->user()->id)
                    ->get();
                }
            )
            ->whereNotIn(
                'id',
                function ($query) {
                    $query->select('user_id')
                    ->from('friends')
                    ->where('friend_id', '=', auth()->user()->id)
                    ->get();
                }
            )
            ->paginate(18)
            ->withQueryString()
            ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name
                ])
            ,
            'search'=> request()->search
            ]
        );
    }

    public function addFriend(Request $request)
    {
        Friend::create([
        'user_id' => auth()->user()->id,
        'friend_id' => request()->friend_id
        ]);
        return redirect()->back();
    }
}
