<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
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
            'content' => $this->content,
            'has_profanity' => $this->has_profanity ?? false,
            'user_id' => $this->user_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'blog_id' => $this->blog_id,
            'parent_id' => $this->parent_id,
            'replies' => BlogCommentResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
