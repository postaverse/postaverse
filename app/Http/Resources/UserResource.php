<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentUser = $request->user();
        $isFollowing = false;
        
        if ($currentUser && $currentUser->id !== $this->id) {
            $isFollowing = $currentUser->follows()->where('following_id', $this->id)->exists();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'handle' => $this->handle,
            'bio' => $this->bio(),
            'profile_photo_url' => $this->profile_photo_url,
            'admin_rank' => $this->admin_rank,
            'verified' => $this->email_verified_at ? true : false,
            'site_verified' => $this->isSiteVerified(),
            'followers_count' => $this->followers()->count(),
            'following_count' => $this->follows()->count(),
            'posts_count' => $this->posts()->count(),
            'is_following' => $isFollowing,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
