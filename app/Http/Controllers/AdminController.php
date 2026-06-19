<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addProduct()
    {
        $categories = Category::query()->orderBy('name')->get();

        return view('admin.store', compact('categories'));
    }
    public function storeProduct(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty' => 'required|integer|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $product = new Product();
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->old_price = $request->input('old_price');
        $product->stock = $request->input('stock');
        $product->warranty = $request->input('warranty');
        $product->image = $request->file('image') ? $request->file('image')->store('products', 'public') : null;
        $product->save();

        $product->categories()->attach($request->input('categories'));

        return redirect()->route('admin.add_product')->with('success', 'Sản phẩm đã được thêm thành công.');
        }
    
        public function orders()
        {
        $pending = Order::with('user','products')
        ->where('status', 'pending')
        ->get();

        $shipping = Order::with('user','products')
        ->where('status', 'shipping')
        ->get();

        $completed = Order::with('user','products')
        ->where('status', 'completed')
        ->get();

        $cancelled = Order::with('user','products')
        ->where('status', 'cancelled')
        ->get();

        return view(
        'admin.orders.index',
        compact(
            'pending',
            'shipping',
            'completed',
            'cancelled'
        )
        );
    }

    public function showOrder($id)
    {
        $order = Order::with(
        'user',
        'products'
    )->findOrFail($id);

    return response()->json(
        $order
    );
    }

    public function updateStatus(Request $request, $id)
    {
    $order = Order::findOrFail($id);

    $order->update([
        'status' =>
            $request->status
    ]);

    return redirect()
    ->back()
    ->with(
        'success',
        'Cập nhật trạng thái thành công'
    );
    }


}
