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
        return redirect('/')->with('success', 'Số dư đã được cập nhật thành công.');

    }
    public function viewAddMoneyForm(){
        return view('user.add_money');
    }
}
