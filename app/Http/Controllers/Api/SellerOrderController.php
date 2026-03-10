<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SellerOrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $seller = $request->user()->sellerProfile()->firstOrFail();
        $productIds = $seller->products()->pluck('id');

        $orderIds = OrderItem::whereIn('product_id', $productIds)->pluck('order_id')->unique();
        $orders = Order::whereIn('id', $orderIds)->with('items.product')->paginate(15);

        return OrderResource::collection($orders);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate(['status' => 'required|in:paid,shipped,completed,cancelled']);
        $seller = $request->user()->sellerProfile()->firstOrFail();
        $productIds = $seller->products()->pluck('id');

        $hasItems = $order->items()->whereIn('product_id', $productIds)->exists();
        abort_if(!$hasItems, 403);

        $order->update(['status' => $request->status]);
        return response()->json(['message' => 'Order status updated.']);
    }
}
