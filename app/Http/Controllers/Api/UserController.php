<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User\User;
use App\Models\Interaction\Follower;
use App\Models\Interaction\Notification;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all users with pagination
     */
    public function index(Request $request)
    {
        $users = User::when($request->search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('handle', 'like', "%{$search}%");
        })->paginate(20);

        return UserResource::collection($users);
    }

    /**
     * Get user profile by ID or handle
     */
    public function show($identifier)
    {
        // Try to find by ID first, then by handle
        $user = User::where('id', $identifier)
            ->orWhere('handle', $identifier)
            ->firstOrFail();

        return new UserResource($user);
    }

    /**
     * Toggle follow/unfollow a user
     */
    public function toggleFollow(Request $request, $userId)
    {
        $userToFollow = User::findOrFail($userId);
        $currentUser = $request->user();

        if ($currentUser->id === $userToFollow->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }

        $existingFollow = Follower::where('follower_id', $currentUser->id)
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($existingFollow) {
            // Unfollow
            $existingFollow->delete();
            return response()->json([
                'message' => 'Successfully unfollowed user',
                'following' => false
            ]);
        } else {
            // Follow
            Follower::create([
                'follower_id' => $currentUser->id,
                'following_id' => $userToFollow->id,
            ]);

            // Create notification
            Notification::create([
                'user_id' => $userToFollow->id,
                'message' => $currentUser->name . ' started following you',
                'link' => '/@' . $currentUser->id,
            ]);

            return response()->json([
                'message' => 'Successfully followed user',
                'following' => true
            ]);
        }
    }

    /**
     * Follow a user
     */
    public function follow(Request $request, $userId)
    {
        $userToFollow = User::findOrFail($userId);
        $currentUser = $request->user();

        if ($currentUser->id === $userToFollow->id) {
            return response()->json(['message' => 'You cannot follow yourself'], 400);
        }

        $existingFollow = Follower::where('follower_id', $currentUser->id)
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($existingFollow) {
            return response()->json(['message' => 'Already following this user'], 400);
        }

        Follower::create([
            'follower_id' => $currentUser->id,
            'following_id' => $userToFollow->id,
        ]);

        // Create notification
        Notification::create([
            'user_id' => $userToFollow->id,
            'message' => $currentUser->name . ' started following you',
            'link' => '/@' . $currentUser->id,
        ]);

        return response()->json([
            'message' => 'Successfully followed user',
            'following' => true
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(Request $request, $userId)
    {
        $userToUnfollow = User::findOrFail($userId);
        $currentUser = $request->user();

        $follow = Follower::where('follower_id', $currentUser->id)
            ->where('following_id', $userToUnfollow->id)
            ->first();

        if (!$follow) {
            return response()->json(['message' => 'Not following this user'], 400);
        }

        $follow->delete();

        return response()->json([
            'message' => 'Successfully unfollowed user',
            'following' => false
        ]);
    }

    /**
     * Get user's followers
     */
    public function followers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers()->paginate(20);

        return UserResource::collection($followers);
    }

    /**
     * Get users that the user is following
     */
    public function following($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->following()->paginate(20);

        return UserResource::collection($following);
    }

    /**
     * Get user's posts
     */
    public function posts($userId)
    {
        $user = User::findOrFail($userId);
        $posts = $user->posts()->with(['user', 'likes', 'comments.user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return \App\Http\Resources\PostResource::collection($posts);
    }

    /**
     * Get user's blogs
     */
    public function blogs($userId)
    {
        $user = User::findOrFail($userId);
        $blogs = $user->blogs()->with(['user', 'likes', 'comments.user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return \App\Http\Resources\BlogResource::collection($blogs);
    }

    /**
     * Check if current user is following another user
     */
    public function isFollowing(Request $request, $userId)
    {
        $currentUser = $request->user();
        $isFollowing = Follower::where('follower_id', $currentUser->id)
            ->where('following_id', $userId)
            ->exists();

        return response()->json(['following' => $isFollowing]);
    }

    /**
     * Get blocked users for current user
     */
    public function getBlockedUsers(Request $request)
    {
        $currentUser = $request->user();
        $blockedUsers = \App\Models\User\BlockedUser::where('user_id', $currentUser->id)->first();
        
        if (!$blockedUsers || empty($blockedUsers->blocked_users)) {
            return response()->json(['data' => []]);
        }

        $blockedIds = array_map('trim', explode(',', $blockedUsers->blocked_users));
        $users = User::whereIn('id', $blockedIds)->get();

        return UserResource::collection($users);
    }

    /**
     * Block a user
     */
    public function blockUser(Request $request, $userId)
    {
        $currentUser = $request->user();
        $userToBlock = User::findOrFail($userId);

        if ($currentUser->id === $userToBlock->id) {
            return response()->json(['message' => 'You cannot block yourself'], 400);
        }

        // Get or create blocked users record
        $blockedUsers = \App\Models\User\BlockedUser::firstOrCreate(
            ['user_id' => $currentUser->id],
            ['blocked_users' => '']
        );

        $blockedIds = array_filter(array_map('trim', explode(',', $blockedUsers->blocked_users)));
        
        if (!in_array($userToBlock->id, $blockedIds)) {
            $blockedIds[] = $userToBlock->id;
            $blockedUsers->blocked_users = implode(',', $blockedIds);
            $blockedUsers->save();

            // Also unfollow if following
            Follower::where('follower_id', $currentUser->id)
                ->where('following_id', $userToBlock->id)
                ->delete();
            Follower::where('follower_id', $userToBlock->id)
                ->where('following_id', $currentUser->id)
                ->delete();
        }

        return response()->json(['message' => 'User blocked successfully']);
    }

    /**
     * Unblock a user
     */
    public function unblockUser(Request $request, $userId)
    {
        $currentUser = $request->user();
        $userToUnblock = User::findOrFail($userId);
        
        $blockedUsers = \App\Models\User\BlockedUser::where('user_id', $currentUser->id)->first();
        
        if (!$blockedUsers || empty($blockedUsers->blocked_users)) {
            return response()->json(['message' => 'User is not blocked'], 400);
        }

        $blockedIds = array_filter(array_map('trim', explode(',', $blockedUsers->blocked_users)));
        $blockedIds = array_filter($blockedIds, function($id) use ($userToUnblock) {
            return $id != $userToUnblock->id;
        });

        if (empty($blockedIds)) {
            $blockedUsers->delete();
        } else {
            $blockedUsers->blocked_users = implode(',', $blockedIds);
            $blockedUsers->save();
        }

        return response()->json(['message' => 'User unblocked successfully']);
    }
}
