<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'has_profanity' => $this->has_profanity,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'likes' => $this->whenLoaded('likes'),
            'likes_count' => $this->likes()->count(),
            'comments_count' => $this->comments()->count(),
            'liked_by_user' => $this->likes()->where('user_id', auth()->id())->exists(),
            'images' => PostImageResource::collection($this->whenLoaded('images')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
