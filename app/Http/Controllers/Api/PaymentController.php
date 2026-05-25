<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function simulate(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
            'status' => 'required|string|in:paid,failed,pending',
        ]);

        $order = Order::with('items.product')->findOrFail($data['order_id']);

        DB::transaction(function () use ($order, $data) {
            if ($data['status'] === 'paid') {
                $order->update(['status' => 'paid']);
            } elseif ($data['status'] === 'failed') {
                // restore stock
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
                $order->update(['status' => 'failed']);
            } else {
                $order->update(['status' => 'pending']);
            }
        });

        return (new OrderResource($order->fresh('items.product')))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
