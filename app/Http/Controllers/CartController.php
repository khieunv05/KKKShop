<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ((int) $product->stock === 0) {
            return back()->with('error', 'Sản phẩm này đã hết hàng.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantity'] >= $product->stock) {
                return back()->with('error', 'Số lượng trong kho không đủ.');
            }
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'product' => $product,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Đã thêm "' . $product->name . '" vào giỏ hàng.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        $quantity = isset($cart[$id])
            ? max(1, min((int) $request->quantity, $product->stock))
            : 1;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        $total = 0;
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $total += $item['product']->price * $item['quantity'];
        }

        $subtotal = $product->price * $quantity;
        $itemCount = array_sum(array_column($cart, 'quantity'));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'subtotal' => number_format($subtotal, 0, ',', '.') . ' ₫',
                'total' => number_format($total, 0, ',', '.') . ' ₫',
                'total_raw' => $total,
                'quantity' => $quantity,
                'item_count' => $itemCount,
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống.');
        }
        return view('cart.checkout', compact('cart'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,qr',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Giỏ hàng trống.');
        }

        $order = null;

        DB::transaction(function () use ($request, $cart, &$order) {
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['product']->price * $item['quantity'];
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'receiver_name' => $request->receiver_name,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'address' => $request->address,
                'total_price' => $total,
                'shipping_fee' => 30000,
                'status' => 'pending',
                'is_paid' => 'unpaid',
            ]);

            foreach ($cart as $id => $item) {
                $order->products()->attach($id, [
                    'quantity' => $item['quantity'],
                    'price' => $item['product']->price,
                ]);
            }
        });

        if ($request->payment_method === 'qr') {
            return redirect()->route('user.add-money', ['order_id' => $order->id])->with('success', 'Đặt hàng thành công! Vui lòng nạp tiền qua QR để thanh toán đơn hàng #' . $order->id);
        }

        return redirect()->route('orders.index')->with('success', 'Đặt hàng thành công!');
    }
}
