<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'display_name'      => $this->display_name,
            'bio'               => $this->bio,
            'profile_image_url' => $this->profile_image_url,
            'cover_image_url'   => $this->cover_image_url,
            'date_of_birth'     => $this->date_of_birth?->toDateString(),
            'gender'            => $this->gender,
            'location'          => $this->location,
            'updated_at'        => $this->updated_at,
        ];
    }
}
