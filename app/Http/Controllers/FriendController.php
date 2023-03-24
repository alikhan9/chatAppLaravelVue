<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Group;
use App\Models\User;
use App\Notifications\FriendRequestNotification;
use App\Notifications\UserNotification;
use Inertia\Inertia;

class FriendController extends Controller
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
            'groups' => Group::where('name', 'like', '%'. request()->search . '%')
                ->with('user')
                ->get(['id','name','owner' ])
            ]
        );
    }

    public function addFriend()
    {
        Friend::create([
        'user_id' => auth()->user()->id,
        'friend_id' => request()->friend_id
        ]);

        $user = User::find(request()->friend_id);
        $user->notify(new FriendRequestNotification());

        return redirect()->back();
    }

    public function acceptFriend()
    {
        Friend::where('user_id', '=', request()->user_id)
        ->where('friend_id', '=', auth()->user()->id)
        ->update(['accepted' => true]);

        $user = User::find(request()->user_id);
        $user->notify(new UserNotification(auth()->user()->name . ' has accepted your friend request.'));
        auth()->user()->notifications
        ->where('id', '=', request()->notification_id)
        ->first()
        ->delete();
        return redirect()->back();
    }

    public function refuseFriend()
    {
        Friend::where('user_id', '=', request()->user_id)
        ->where('friend_id', '=', auth()->user()->id)
        ->delete();

        $user = User::find(request()->user_id);

        $user->notify(new UserNotification(auth()->user()->name . ' has refused your friend request.'));
        auth()->user()->notifications->where('id', '=', request()->notification_id)->first()->delete();

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
