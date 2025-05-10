<?php

namespace App\Http\Controllers;

use App\Models\User\User;
use App\Models\Interaction\Follower;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Follow a user
     * 
     * @param User $user The user to follow
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        // Use direct database interaction for reliability in tests
        if (auth()->check() && auth()->id() != $user->id) {
            // Check if already following
            $existingFollow = Follower::where('follower_id', auth()->id())
                ->where('following_id', $user->id)
                ->first();
                
            if (!$existingFollow) {
                Follower::create([
                    'follower_id' => auth()->id(),
                    'following_id' => $user->id
                ]);
            }
        }
        
        return back();
    }
    
    /**
     * Unfollow a user
     * 
     * @param User $user The user to unfollow
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        // Use direct database interaction for reliability in tests
        if (auth()->check() && auth()->id() != $user->id) {
            Follower::where('follower_id', auth()->id())
                ->where('following_id', $user->id)
                ->delete();
        }
        
        return back();
    }
}
