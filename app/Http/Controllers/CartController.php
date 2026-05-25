<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $productIds = array_map('intval', array_keys($cart));
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = [];
        foreach ($cart as $id => $item) {
            $product = $products->get((int) $id);
            $cartItems[$id] = array_merge($item, [
                'stock' => $product?->stock ?? 0,
            ]);
        }

        $total = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function checkout()
    {
        $cart = session('cart', []);
        $productIds = array_map('intval', array_keys($cart));
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = [];
        foreach ($cart as $id => $item) {
            $product = $products->get((int) $id);
            $cartItems[$id] = array_merge($item, [
                'stock' => $product?->stock ?? 0,
            ]);
        }

        $total = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('checkout', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $cart = session('cart', []);
        $productId = (string) $product->id;
        $existingQty = $cart[$productId]['quantity'] ?? 0;
        $newQty = $existingQty + $data['quantity'];

        if ($newQty > $product->stock) {
            $message = 'Số lượng vượt quá tồn kho hiện có.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->withErrors(['quantity' => $message]);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $data['quantity'],
            ];
        }

        session(['cart' => $cart]);

        $cartCount = collect($cart)->sum('quantity');
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Đã thêm vào giỏ hàng.',
                'cartCount' => $cartCount,
            ]);
        }

        return back()->with('success', 'Đã thêm vào giỏ hàng.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session('cart', []);
        $productId = (string) $data['product_id'];

        $product = Product::find($data['product_id']);
        if (!$product) {
            return redirect()->route('cart.index');
        }

        if ($data['quantity'] > $product->stock) {
            $message = 'Số lượng vượt quá tồn kho hiện có.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return back()->withErrors(['quantity' => $message]);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $data['quantity'];
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            $cartCount = collect($cart)->sum('quantity');
            $lineTotal = ($cart[$productId]['price'] ?? 0) * $data['quantity'];
            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            return response()->json([
                'message' => 'Đã cập nhật số lượng.',
                'cartCount' => $cartCount,
                'lineTotal' => $lineTotal,
                'total' => $total,
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
        ]);

        $cart = session('cart', []);
        $productId = (string) $data['product_id'];

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }
}
