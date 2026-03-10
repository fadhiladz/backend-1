<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'status'         => $this->status,
            'seller'         => new SellerProfileResource($this->whenLoaded('seller')),
            'category'       => new ProductCategoryResource($this->whenLoaded('category')),
            'images'         => ProductImageResource::collection($this->whenLoaded('images')),
            'reviews_count'  => $this->whenCounted('reviews'),
            'avg_rating'     => $this->when(isset($this->avg_rating), fn() => round($this->avg_rating, 1)),
            'created_at'     => $this->created_at,
        ];
    }
}
