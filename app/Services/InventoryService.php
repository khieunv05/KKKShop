<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public function deductForPaidOrder(Order $order): void
    {
        $order->loadMissing('products');

        DB::transaction(function () use ($order) {
            foreach ($order->products as $product) {
                $quantity = (int) $product->pivot->quantity;
                $currentStock = (int) $product->stock;

                if ($currentStock < $quantity) {
                    throw new RuntimeException('Không đủ tồn kho cho sản phẩm: ' . $product->name);
                }

                Product::query()->whereKey($product->id)->update([
                    'stock' => $currentStock - $quantity,
                ]);
            }
        });
    }

    public function restoreForCancelledOrder(Order $order): void
    {
        $order->loadMissing('products');

        DB::transaction(function () use ($order) {
            foreach ($order->products as $product) {
                $quantity = (int) $product->pivot->quantity;

                Product::query()->whereKey($product->id)->update([
                    'stock' => (int) $product->stock + $quantity,
                ]);
            }
        });
    }
}
