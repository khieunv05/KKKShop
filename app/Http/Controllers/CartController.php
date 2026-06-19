<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CartController extends Controller
{
    public function add($id)
{
    $product = Product::findOrFail($id);

    $cart = session()->get('cart', []);

    if(isset($cart[$id]))
    {
        $cart[$id]['quantity']++;
    }
    else
    {
        $cart[$id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => 1
        ];
    }

    session()->put('cart',$cart);

    return back();
}
    
    public function index()
{
    $cart = session()->get('cart',[]);

    return view('cart.index',compact('cart'));
}

    public function update(Request $request,$id)
{
    $cart = session()->get('cart',[]);

    if(isset($cart[$id]))
    {
        $cart[$id]['quantity'] = $request->quantity;

        session()->put('cart',$cart);
    }

    return response()->json([
        'success'=>true
    ]);
}
    public function remove($id)
{
    $cart = session()->get('cart',[]);

    unset($cart[$id]);

    session()->put('cart',$cart);

    return response()->json([
        'success'=>true
    ]);
}

    public function checkout(Request $request)
{
    DB::beginTransaction();

    try {

        $cart = session()->get('cart',[]);

        if(empty($cart))
        {
            return back();
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'status' => 'pending',
            'is_paid' => 'unpaid',
            'shipping_fee' => 30000
        ]);

        foreach($cart as $item)
        {
            $order->products()->attach(
                $item['id'],
                [
                    'quantity' => $item['quantity']
                ]
            );

            Product::where(
                'id',
                $item['id']
            )->decrement(
                'stock',
                $item['quantity']
            );
        }

        session()->forget('cart');

        DB::commit();

        return redirect()
            ->route('orders.index');

    } catch (\Exception $e) {

        DB::rollBack();

        return back();
    }
}
}
