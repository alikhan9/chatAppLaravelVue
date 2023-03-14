<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render(
        'Home',
        [
            'friends' => auth()->user()->friends,
            'friend_requests' => auth()->user()->pendingFriendsFrom
            ->map(fn ($user) =>
            [
                'id' => $user->id,
                'name' => $user->name
            ])
        ]
    );
})->middleware(['auth', 'verified'])->name('home');


Route::get('/user/profile', function () {
    return Inertia::render(
        'UserProfile',
        [
            'user' => auth()->user(),
            'friends' => auth()->user()->friends,
            'friend_requests' => auth()->user()->pendingFriendsFrom
            ->map(fn ($user) =>
            [
                'id' => $user->id,
                'name' => $user->name
            ])
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
        'friend_requests' => auth()->user()->pendingFriendsFrom
            ->map(fn ($user) =>
            [
                'id' => $user->id,
                'name' => $user->name
            ])
    ]
    );
})->name('publicProfile')->middleware(['auth', 'verified']);


Route::get('/search', [UserController::class, 'search'])->middleware(['auth', 'verified']);
Route::post('/addFriend', [UserController::class, 'addFriend'])->middleware(['auth', 'verified']);
Route::post('/acceptFriend', [UserController::class, 'acceptFriend'])->middleware(['auth', 'verified']);
Route::post('/refuseFriend', [UserController::class, 'refuseFriend'])->middleware(['auth', 'verified']);
Route::delete('/deleteFriend/{friend_id}', [UserController::class, 'deleteFriend'])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/notifications/markAsRead', [NotificationController::class,'markNotificationsAsRead']);
    Route::delete('/notifications/delete', [NotificationController::class,'destroy']);
});


require __DIR__.'/auth.php';
