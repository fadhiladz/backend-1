<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreProductReviewRequest;
use App\Http\Resources\ProductReviewResource;
use App\Models\OrderItem;

class ProductReviewController extends Controller
{
    public function store(StoreProductReviewRequest $request, OrderItem $orderItem): ProductReviewResource
    {
        abort_if($orderItem->order->buyer_id !== $request->user()->id, 403);
        abort_if($orderItem->review()->exists(), 422, 'Already reviewed.');
        abort_if($orderItem->order->status !== 'completed', 422, 'Order must be completed before reviewing.');

        $review = $orderItem->review()->create([
            'product_id' => $orderItem->product_id,
            'buyer_id'   => $request->user()->id,
            'rating'     => $request->rating,
            'review_text'=> $request->review_text,
        ]);

        return new ProductReviewResource($review->load('buyer.profile'));
    }
}
