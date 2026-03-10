<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(StoreProductImageRequest $request, Product $product): ProductImageResource
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        abort_if($product->seller_id !== $seller->id, 403);
        $image = $product->images()->create($request->validated());
        return new ProductImageResource($image);
    }

    public function destroy(Request $request, Product $product, ProductImage $image): JsonResponse
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        abort_if($product->seller_id !== $seller->id, 403);
        $image->delete();
        return response()->json(['message' => 'Image removed.']);
    }
}
