<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::with('seller', 'category', 'images')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('status', 'active')
            ->paginate(20);
        return ProductResource::collection($products);
    }

    public function store(StoreProductRequest $request): ProductResource
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        $product = $seller->products()->create($request->validated());
        return new ProductResource($product->load('seller', 'category', 'images'));
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource(
            $product->load('seller.user.profile', 'category', 'images', 'reviews.buyer.profile')
                ->loadCount('reviews')
                ->loadAvg('reviews', 'rating')
        );
    }

    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        abort_if($product->seller_id !== $seller->id, 403);
        $product->update($request->validated());
        return new ProductResource($product->load('seller', 'category', 'images'));
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        abort_if($product->seller_id !== $seller->id, 403);
        $product->delete();
        return response()->json(['message' => 'Product deleted.']);
    }
}
