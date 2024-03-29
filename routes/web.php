<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\PrivateMessageController;
use App\Http\Controllers\PublicMessageController;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {

    if(request()->id !== null && (auth()->user()->friends()->where('id', '=', request()->id)->get()->count() == 0) || request()->group_id !== null && Group::where('id', '=', request()->group_id)->get()->count() == 0) {
        return redirect('/');
    }

    $messages = [];

    if(request()->id) {
        $messages = auth()->user()->privateMessages->filter(function ($message) {
            return $message->to == request()->id || $message->from == request()->id;
        })->map(function ($message) {
            $message->created_at_human = $message->created_at->diffForHumans();
            return $message;
        })->toArray();
    }
    if(request()->group_id) {
        $group = Group::find(request()->group_id);
        if($group) {
            $messages = $group->messages->map(function ($message) {
                $message->created_at_human = $message->created_at->diffForHumans();
                return $message;
            })->toArray();
        }
    }

    $groups = [...auth()->user()->groups,...Group::whereIn(
        'id',
        function ($query) {
            $query->select('group_id')
            ->from('group_members')
            ->where('user_id', '=', auth()->user()->id)
            ->get();
        }
    )->get()];


    return Inertia::render(
        'Home',
        [
            'friends' => auth()->user()->friends,
            'messages' => $messages,
            'currentChatId' => request()->id ? request()->id : request()->group_id,
            'groups' => $groups,
            'toUser' => request()->id ? true : false,
            'groupId' => request()->group_id
        ]
    );

})->middleware(['auth', 'verified'])->name('home');


Route::get('/user/profile', function () {
    return Inertia::render(
        'UserProfile',
        [
            'friends' => auth()->user()->friends,
        ]
    );
})->name('user.profile')->middleware(['auth', 'verified']);


Route::get('/public/profile/{id}', function () {
    if (request()->id == auth()->user()->id) {
        return to_route('user.profile');
    }

    $user = User::find(request()->id);
    return Inertia::render(
        'PublicProfile',
        [
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->diffForHumans(),
        ],
        'friends' => User::findOrFail(request()->id)->friends,
        'user_friends' => auth()->user()->friends,
    ]
    );
})->name('publicProfile')->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/search', [FriendController::class, 'search']);
    Route::get('/manageFriends', [FriendController::class, 'index'])->name('friends.manage'); // A faire
    Route::post('/addFriend', [FriendController::class, 'addFriend']);
    Route::post('/acceptFriend', [FriendController::class, 'acceptFriend']);
    Route::post('/refuseFriend', [FriendController::class, 'refuseFriend']);
    Route::delete('/deleteFriend/{friend_id}', [FriendController::class, 'deleteFriend']);

    Route::delete('/notifications/delete', [NotificationController::class,'destroy']);
    Route::post('/notifications/markAsRead', [NotificationController::class,'markNotificationsAsRead']);

    Route::post('/message/private', [PrivateMessageController::class,'store']);
    Route::post('/message/public', [PublicMessageController::class,'store']);

    Route::post('/createGroup', [GroupController::class, 'store']);
    Route::post('/joinGroup', [GroupController::class, 'join']);
    Route::delete('/deleteGroup/{group}', [GroupController::class, 'destroy']);
    Route::delete('/leaveGroup/{group_id}', [GroupMemberController::class, 'destroy']);
    Route::get('/groupProfile/{group}', [GroupController::class, 'profile'])->name('groupProfile');
});


require __DIR__.'/auth.php';
