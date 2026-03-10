<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'rating'      => $this->rating,
            'review_text' => $this->review_text,
            'buyer'       => new UserResource($this->whenLoaded('buyer')),
            'created_at'  => $this->created_at,
        ];
    }
}
