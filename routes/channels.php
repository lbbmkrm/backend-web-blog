<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info('Channel auth attempt', [
        'user' => $user ? $user->toArray() : null,
        'id' => $id,
        'auth' => Auth::check()
    ]);
    if (!$user) {
        Log::warning('User not authenticated for channel');
        return false;
    }
    return (int) $user->id === (int) $id;
});
