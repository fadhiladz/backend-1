<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'user'          => new UserResource($this->whenLoaded('user')),
            'postable_type' => $this->postable_type,
            'postable_id'   => $this->postable_id,
            'content'       => $this->content,
            'visibility'    => $this->visibility,
            'media'         => PostMediaResource::collection($this->whenLoaded('media')),
            'likes_count'   => $this->whenCounted('likes'),
            'comments_count'=> $this->whenCounted('comments'),
            'liked_by_user' => $this->when(
                isset($this->liked_by_user),
                fn() => (bool) $this->liked_by_user
            ),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
