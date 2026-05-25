<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $orders = Order::where('user_id', $user->id)->with('items.product')->latest()->get();
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load('items.product');
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $order = DB::transaction(function () use ($data, $user) {
            $total = 0;
            $itemsData = [];

            foreach ($data['items'] as $it) {
                $product = Product::lockForUpdate()->find($it['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found');
                }
                if ($product->stock < $it['quantity']) {
                    throw new \Exception("Insufficient stock for product {$product->id}");
                }

                $lineTotal = $product->price * $it['quantity'];
                $total += $lineTotal;

                $itemsData[] = [
                    'product' => $product,
                    'price' => $product->price,
                    'quantity' => $it['quantity'],
                    'total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $data['shipping_address'] ?? null,
                'phone' => $data['phone'] ?? null,
                'payment_method' => $data['payment_method'] ?? null,
            ]);

            foreach ($itemsData as $it) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $it['product']->id,
                    'price' => $it['price'],
                    'quantity' => $it['quantity'],
                    'total' => $it['total'],
                ]);

                // Decrement stock
                $it['product']->decrement('stock', $it['quantity']);
            }

            return $order;
        });

        return (new OrderResource($order->load('items.product')))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
