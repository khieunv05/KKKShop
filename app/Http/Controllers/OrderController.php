<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function markPaid($id, InventoryService $inventoryService)
    {
        $order = Order::with('products')->findOrFail($id);

        if ($order->is_paid !== 'paid') {
            $inventoryService->deductForPaidOrder($order);
        }

        $order->update([
            'is_paid' => 'paid',
            'status' => 'completed',
        ]);

        return back()->with('success', 'Đơn hàng đã được xác nhận thanh toán.');
    }

    public function cancelByAdmin($id, InventoryService $inventoryService)
    {
        $order = Order::with('products')->findOrFail($id);

        if ($order->status === 'cancelled') {
            return back()->with('error', 'Đơn hàng đã bị hủy trước đó.');
        }

        if ($order->is_paid === 'paid') {
            $inventoryService->restoreForCancelledOrder($order);

            if ($order->payment_method === 'qr') {
                $user = $order->user;
                $totalDue = $order->total_price + $order->shipping_fee;
                $user->current_balance += $totalDue;
                $user->save();
            }
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Đơn hàng đã bị hủy' . ($order->is_paid === 'paid' ? ' và đã hoàn tiền.' : '.'));
    }

    public function confirmByUser(Order $order, InventoryService $inventoryService)
    {
        if ($order->status !== 'cancelled') {
            $inventoryService->deductForPaidOrder($order);

            $cart = session()->get('cart', []);
            foreach ($order->products as $product) {
                if (isset($cart[$product->id])) {
                    unset($cart[$product->id]);
                }
            }
            session()->put('cart', $cart);

            $order->update([
                'status' => 'completed',
                'is_paid' => 'paid',
            ]);
            return back()->with('success', 'Đơn hàng đã được xác nhận.');
        }
        return back()->with('error', 'Đơn hàng đã bị hủy và không thể xác nhận.');
    }

    public function payByBalance(Request $request, $id, InventoryService $inventoryService)
    {
        $order = Order::findOrFail($id);
        $user = auth()->user();

        if ($order->user_id !== $user->id) {
            return back()->with('error', 'Bạn không có quyền thanh toán đơn này.');
        }

        if ($order->is_paid === 'paid') {
            return back()->with('error', 'Đơn hàng đã được thanh toán.');
        }

        $totalDue = $order->total_price + $order->shipping_fee;

        if ($user->current_balance < $totalDue) {
            return redirect()->route('user.add-money', ['order_id' => $order->id])
                ->with('error', 'Số dư không đủ. Vui lòng nạp thêm.');
        }

        $user->current_balance -= $totalDue;
        $user->save();

        $inventoryService->deductForPaidOrder($order);

        $order->update([
            'is_paid' => 'paid',
        ]);

        $cart = session()->get('cart', []);
        foreach ($order->products as $product) {
            if (isset($cart[$product->id])) {
                unset($cart[$product->id]);
            }
        }
        session()->put('cart', $cart);

        return redirect()->route('orders.index')->with('success', 'Thanh toán đơn hàng #' . $order->id . ' thành công!');
    }
}
