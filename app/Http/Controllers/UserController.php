<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function viewOrders(){
        $user = User::findOrFail(auth()->id());
        $orders = $user->orders()->with('products')->get();
        return view('user.order', compact('orders'));
    }

    public function addMoney(Request $request){
        $user = auth()->user();
        $user->current_balance += 100000000;
        $user->save();

        $orderId = $request->input('order_id');
        if ($orderId) {
            return redirect()->route('user.add-money', ['order_id' => $orderId])
                ->with('success', 'Nạp tiền thành công! Số dư hiện tại: ' . number_format($user->current_balance, 0, ',', '.') . ' ₫');
        }

        return redirect('/')->with('success', 'Số dư đã được cập nhật thành công.');
    }

    public function viewAddMoneyForm(Request $request){
        $orderId = $request->input('order_id');
        $order = null;

        if ($orderId) {
            $order = \App\Models\Order::with('products')->find($orderId);
        }

        return view('user.add_money', compact('order'));
    }
}
