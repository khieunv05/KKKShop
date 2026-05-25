<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn trống.');
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'shipping_address' => $validated['shipping_address'],
            'notes' => $validated['notes'],
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $id => $details) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $details['quantity'],
                'price' => $details['price'],
            ]);

            // Decrement product stock
            $product = Product::find($id);
            if ($product) {
                $product->stock -= $details['quantity'];
                $product->save();
            }
        }

        Session::forget('cart');

        return redirect()->route('checkout.success', ['order' => $order->id]);
    }

    public function success(Order $order)
    {
        // Make sure the user can only see their own order success page
        if (Auth::id() !== $order->user_id) {
            abort(403);
        }
        return view('checkout.success', compact('order'));
    }
}
