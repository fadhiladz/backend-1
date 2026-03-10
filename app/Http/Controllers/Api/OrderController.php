<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()->with('items.product.images')->latest()->paginate(15);
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $order = DB::transaction(function () use ($request) {
            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                if ($product->stock_quantity < $item['quantity']) {
                    abort(422, "Insufficient stock for product: {$product->name}");
                }
                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;
                $itemsData[] = [
                    'product'   => $product,
                    'quantity'  => $item['quantity'],
                    'unit_price'=> $product->price,
                    'subtotal'  => $subtotal,
                ];
            }

            $order = Order::create([
                'buyer_id'     => $request->user()->id,
                'status'       => 'pending',
                'total_amount' => $total,
            ]);

            foreach ($itemsData as $itemData) {
                $product = $itemData['product'];
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'subtotal'   => $itemData['subtotal'],
                ]);
                $product->decrement('stock_quantity', $itemData['quantity']);
            }

            return $order;
        });

        return new OrderResource($order->load('items.product.images'));
    }

    public function show(Request $request, Order $order): OrderResource
    {
        abort_if($order->buyer_id !== $request->user()->id, 403);
        return new OrderResource($order->load('items.product.images'));
    }

    public function cancel(Request $request, Order $order): JsonResponse
    {
        abort_if($order->buyer_id !== $request->user()->id, 403);
        abort_if(!in_array($order->status, ['pending', 'paid']), 422, 'Order cannot be cancelled.');
        $order->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Order cancelled.']);
    }
}
