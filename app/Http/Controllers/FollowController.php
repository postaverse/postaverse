<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $userToFollow)
    {
        $currentUser = auth()->user();
        $currentUser->following()->attach($userToFollow);

        // Redirect back or to a specific page with a success message
        return back()->with('success', 'You are now following ' . $userToFollow->name);
    }

    public function unfollow(User $userToUnfollow)
    {
        $currentUser = auth()->user();
        $currentUser->following()->detach($userToUnfollow);

        // Redirect back or to a specific page with a success message
        return back()->with('success', 'You have unfollowed ' . $userToUnfollow->name);
    }
}