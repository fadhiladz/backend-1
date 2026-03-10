<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'description'     => $this->description,
            'sport'           => new SportResource($this->whenLoaded('sport')),
            'cover_image_url' => $this->cover_image_url,
            'owner'           => new UserResource($this->whenLoaded('owner')),
            'visibility'      => $this->visibility,
            'members_count'   => $this->whenCounted('members'),
            'my_role'         => $this->when(
                isset($this->pivot),
                fn() => $this->pivot?->role
            ),
            'created_at'      => $this->created_at,
        ];
    }
}
