<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;

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

        if ($order->status !== 'cancelled') {
            $inventoryService->restoreForCancelledOrder($order);
        }

        $order->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Đơn hàng đã bị hủy và kho được hoàn lại.');
    }
    public function confirmByUser(Order $order){
        if ($order->status !== 'cancelled') {
            $order->update([
                'status' => 'completed',
                'is_paid' => 'paid',
            ]);
            return back()->with('success', 'Đơn hàng đã được xác nhận.');
        } else {
            return back()->with('error', 'Đơn hàng đã bị hủy và không thể xác nhận.');
        }
    }
}
