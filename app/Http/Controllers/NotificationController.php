<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function markNotificationsAsRead()
    {
        foreach (auth()->user()->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    }

    public function destroy()
    {
        auth()->user()->notifications()->where('type', '=', 'App\Notifications\UserRequestNotification')->delete();
        return redirect()->back();
    }
}
