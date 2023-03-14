<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use App\Notifications\userNotif;
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
            'search'=> request()->search,
            'friend_requests' => auth()->user()->pendingFriendsFrom
            ->map(fn ($user) =>
            [
                'id' => $user->id,
                'name' => $user->name
            ])
            ]
        );
    }

    public function addFriend()
    {
        Friend::create([
        'user_id' => auth()->user()->id,
        'friend_id' => request()->friend_id
        ]);
        return redirect()->back();
    }

    public function acceptFriend()
    {
        Friend::where('user_id', '=', request()->user_id)
        ->where('friend_id', '=', auth()->user()->id)
        ->update(['accepted' => true]);

        $user = User::find(request()->user_id);

        $user->notify(new userNotif(auth()->user()->name . ' has accepted your friend request.'));
        return redirect()->back();
    }

    public function refuseFriend()
    {
        $friend = Friend::where('user_id', '=', request()->user_id)
        ->where('friend_id', '=', auth()->user()->id)
        ->first();

        Friend::where('user_id', '=', request()->user_id)
        ->where('friend_id', '=', auth()->user()->id)
        ->delete();

        auth()->user()->notify(new userNotif($friend, auth()->user()));
        return redirect()->back();
    }

    public function deleteFriend()
    {
        Friend::whereIn('user_id', [request()->friend_id,auth()->user()->id])
        ->whereIn('friend_id', [request()->friend_id,auth()->user()->id])
        ->delete();

        return redirect()->back();
    }
}
