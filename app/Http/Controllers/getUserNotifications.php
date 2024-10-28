<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class getUserNotifications extends Controller
{
    public function getUserNotifications()
{
    $userId = Auth::id();

    // Fetch notifications where sent_to is the logged-in user
    $notifications = Notification::where('sent_to', $userId)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('dashboard', compact('notifications'));
}
}
