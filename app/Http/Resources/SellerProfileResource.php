<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'shop_name'        => $this->shop_name,
            'shop_description' => $this->shop_description,
            'shop_logo_url'    => $this->shop_logo_url,
            'verified'         => $this->verified,
            'status'           => $this->status,
            'user'             => new UserResource($this->whenLoaded('user')),
            'created_at'       => $this->created_at,
        ];
    }
}
