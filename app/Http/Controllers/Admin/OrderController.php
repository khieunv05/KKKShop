<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);
        $stats = [
            'total_orders' => Order::count(),
            'paid_orders' => Order::where('status', 'paid')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'failed_orders' => Order::where('status', 'failed')->count(),
            'revenue' => Order::where('status', 'paid')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        return view('admin.orders.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required|string|in:pending,paid,failed',
            'shipping_address' => 'nullable|string',
            'phone' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = DB::transaction(function () use ($data) {
                $total = 0;
                $itemsData = [];

                foreach ($data['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) {
                        throw ValidationException::withMessages([
                            'items' => 'Sản phẩm không tồn tại.'
                        ]);
                    }

                    if ($data['status'] !== 'failed' && $product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'items' => "Không đủ tồn kho cho {$product->name}."
                        ]);
                    }

                    $lineTotal = $product->price * $item['quantity'];
                    $total += $lineTotal;

                    $itemsData[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'line_total' => $lineTotal,
                    ];
                }

                $order = Order::create([
                    'user_id' => $data['user_id'],
                    'total' => $total,
                    'status' => $data['status'],
                    'shipping_address' => $data['shipping_address'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'payment_method' => $data['payment_method'] ?? null,
                ]);

                foreach ($itemsData as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'price' => $item['product']->price,
                        'quantity' => $item['quantity'],
                        'total' => $item['line_total'],
                    ]);

                    if ($data['status'] !== 'failed') {
                        $item['product']->decrement('stock', $item['quantity']);
                    }
                }

                return $order;
            });
        } catch (ValidationException $e) {
            throw $e;
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Tạo giao dịch thành công.');
    }

    public function edit(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,paid,failed',
            'shipping_address' => 'nullable|string',
            'phone' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        DB::transaction(function () use ($order, $data) {
            $prevStatus = $order->status;
            $newStatus = $data['status'];

            if ($prevStatus !== 'failed' && $newStatus === 'failed') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            if ($prevStatus === 'failed' && $newStatus !== 'failed') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product && $product->stock < $item->quantity) {
                        throw ValidationException::withMessages([
                            'status' => "Không đủ tồn kho để đổi trạng thái cho {$product->name}."
                        ]);
                    }
                }

                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => $newStatus,
                'shipping_address' => $data['shipping_address'] ?? $order->shipping_address,
                'phone' => $data['phone'] ?? $order->phone,
                'payment_method' => $data['payment_method'] ?? $order->payment_method,
            ]);
        });

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Cập nhật giao dịch thành công.');
    }

    public function destroy(Order $order)
    {
        DB::transaction(function () use ($order) {
            if ($order->status !== 'failed') {
                $order->load('items.product');
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->delete();
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã xóa giao dịch.');
    }
}
