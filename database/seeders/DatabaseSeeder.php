<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'phone' => '0900000000',
                'address' => '123 Đường ABC',
                'city' => 'HN',
                'role' => 'admin',
            ]
        );
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'phone' => '0900000000',
                'address' => '123 Đường ABC',
                'city' => 'HN',
                'role' => 'user',
            ]
        );

        $categories = collect([
            [
                'name' => 'Gaming PC',
                'description' => 'Bộ máy tính hoàn chỉnh phục vụ chơi game.',
            ],
            [
                'name' => 'CPU',
                'description' => 'Bộ vi xử lý cho máy tính.',
            ],
            [
                'name' => 'Mainboard',
                'description' => 'Bo mạch chủ và linh kiện liên quan.',
            ],
            [
                'name' => 'RAM',
                'description' => 'Bộ nhớ tạm cho hệ thống.',
            ],
            [
                'name' => 'SSD',
                'description' => 'Ổ lưu trữ tốc độ cao.',
            ],
        ]);

        foreach ($categories as $categoryData) {
            DB::table('categories')->updateOrInsert(
                ['name' => $categoryData['name']],
                ['description' => $categoryData['description']]
            );
        }

        $categoryModels = Category::query()->get()->keyBy('name');

        $products = collect([
            [
                'name' => 'PC Gaming Ryzen 5 5600 + RTX 4060',
                'description' => 'Bộ PC chơi game tầm trung, phù hợp 1080p/2K.',
                'price' => 22990000,
                'old_price' => 24990000,
                'stock' => 8,
                'warranty' => 36,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'CPU Intel Core i7-14700K',
                'description' => 'CPU hiệu năng cao cho gaming và làm việc đa nhiệm.',
                'price' => 11290000,
                'old_price' => null,
                'stock' => 15,
                'warranty' => 36,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Mainboard B760M DDR5',
                'description' => 'Bo mạch chủ chuẩn mATX hỗ trợ DDR5.',
                'price' => 3890000,
                'old_price' => 4290000,
                'stock' => 20,
                'warranty' => 24,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'RAM 32GB DDR5 6000MHz',
                'description' => 'Bộ nhớ dung lượng lớn cho workstation và game.',
                'price' => 2790000,
                'old_price' => null,
                'stock' => 30,
                'warranty' => 36,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'SSD NVMe 1TB Gen4',
                'description' => 'Ổ cứng tốc độ cao cho hệ điều hành và game.',
                'price' => 1890000,
                'old_price' => 2190000,
                'stock' => 25,
                'warranty' => 36,
                'image' => null,
                'is_active' => true,
            ],
        ]);

        $productModels = $products->map(function (array $productData) {
            return Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        })->keyBy('name');

        $categoryAssignments = [
            'PC Gaming Ryzen 5 5600 + RTX 4060' => ['Gaming PC'],
            'CPU Intel Core i7-14700K' => ['CPU'],
            'Mainboard B760M DDR5' => ['Mainboard'],
            'RAM 32GB DDR5 6000MHz' => ['RAM'],
            'SSD NVMe 1TB Gen4' => ['SSD'],
        ];

        foreach ($categoryAssignments as $productName => $categoryNames) {
            $product = $productModels->get($productName);

            if (! $product) {
                continue;
            }

            foreach ($categoryNames as $categoryName) {
                $categoryModels->get($categoryName)?->products()->syncWithoutDetaching([$product->id]);
            }
        }

        $orders = collect([
            [
                'address' => '123 Đường ABC, Hà Nội',
                'is_paid' => 'paid',
                'status' => 'completed',
                'shipping_fee' => 30000,
                'items' => [
                    ['product' => 'PC Gaming Ryzen 5 5600 + RTX 4060', 'quantity' => 1],
                    ['product' => 'SSD NVMe 1TB Gen4', 'quantity' => 2],
                ],
            ],
            [
                'address' => '45 Nguyễn Huệ, Hồ Chí Minh',
                'is_paid' => 'unpaid',
                'status' => 'pending',
                'shipping_fee' => 45000,
                'items' => [
                    ['product' => 'CPU Intel Core i7-14700K', 'quantity' => 1],
                    ['product' => 'Mainboard B760M DDR5', 'quantity' => 1],
                    ['product' => 'RAM 32GB DDR5 6000MHz', 'quantity' => 2],
                ],
            ],
            [
                'address' => '88 Trần Phú, Đà Nẵng',
                'is_paid' => 'paid',
                'status' => 'shipping',
                'shipping_fee' => 25000,
                'items' => [
                    ['product' => 'SSD NVMe 1TB Gen4', 'quantity' => 1],
                    ['product' => 'RAM 32GB DDR5 6000MHz', 'quantity' => 1],
                ],
            ],
        ]);

        foreach ($orders as $orderData) {
            $syncData = [];
            $totalPrice = 0;

            foreach ($orderData['items'] as $item) {
                $product = $productModels->firstWhere('name', $item['product']);

                if (! $product) {
                    continue;
                }

                $quantity = (int) $item['quantity'];
                $price = (int) $product->price;

                $syncData[$product->id] = [
                    'quantity' => $quantity,
                    'price' => $price,
                ];

                $totalPrice += $price * $quantity;
            }

            $order = Order::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'address' => $orderData['address'],
                ],
                [
                    'is_paid' => $orderData['is_paid'],
                    'status' => $orderData['status'],
                    'shipping_fee' => $orderData['shipping_fee'],
                    'total_price' => $totalPrice,
                ]
            );

            $order->products()->sync($syncData);
        }
    }
}
