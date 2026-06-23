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

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('order_product')->delete();
        DB::table('category_product')->delete();
        DB::table('orders')->delete();
        DB::table('categories')->delete();
        DB::table('products')->delete();
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password'), 'phone' => '0900000000', 'address' => '123 Đường ABC', 'city' => 'HN', 'role' => 'admin']
        );
        $user = User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password'), 'phone' => '0900000000', 'address' => '123 Đường ABC', 'city' => 'HN', 'role' => 'user']
        );

        $categories = collect();
        foreach ([
            'PC Gaming' => 'Máy tính chơi game hiệu năng cao',
            'PC Workstation' => 'Máy tính đồ họa và làm việc chuyên nghiệp',
            'CPU' => 'Bộ vi xử lý',
            'Mainboard' => 'Bo mạch chủ',
            'RAM' => 'Bộ nhớ trong',
            'SSD' => 'Ổ cứng thể rắn',
        ] as $name => $desc) {
            $categories->put($name, Category::updateOrCreate(['name' => $name], ['description' => $desc]));
        }

        $products = collect();
        $productData = [
            ['PC Gaming Ryzen 5 5600 + RTX 4060', 22990000, 24990000, 8, 36, ['PC Gaming']],
            ['PC Gaming Intel i5 + RTX 4070', 28990000, null, 5, 36, ['PC Gaming']],
            ['PC Workstation Intel i7 + 32GB RAM', 35990000, 38990000, 3, 36, ['PC Workstation']],
            ['CPU Intel Core i7-14700K', 11290000, null, 15, 36, ['CPU']],
            ['CPU AMD Ryzen 7 7800X3D', 14290000, null, 10, 36, ['CPU']],
            ['Mainboard B760M DDR5', 3890000, 4290000, 20, 24, ['Mainboard']],
            ['Mainboard X670E AORUS', 8990000, null, 7, 24, ['Mainboard']],
            ['RAM 32GB DDR5 6000MHz', 2790000, null, 30, 36, ['RAM']],
            ['RAM 16GB DDR4 3200MHz', 1190000, 1390000, 40, 36, ['RAM']],
            ['SSD NVMe 1TB Gen4', 1890000, 2190000, 25, 36, ['SSD']],
            ['SSD SATA 480GB', 690000, 890000, 35, 24, ['SSD']],
        ];

        foreach ($productData as [$name, $price, $oldPrice, $stock, $warranty, $catNames]) {
            $product = Product::updateOrCreate(
                ['name' => $name],
                ['description' => $name, 'price' => $price, 'old_price' => $oldPrice, 'stock' => $stock, 'warranty' => $warranty, 'image' => null, 'is_active' => true]
            );
            $products->put($name, $product);
            foreach ($catNames as $catName) {
                $categories->get($catName)?->products()->syncWithoutDetaching([$product->id]);
            }
        }

        $orders = [
            ['123 Đường ABC, Hà Nội', 'paid', 'completed', 30000, ['PC Gaming Ryzen 5 5600 + RTX 4060' => 1, 'SSD NVMe 1TB Gen4' => 2]],
            ['45 Nguyễn Huệ, Hồ Chí Minh', 'unpaid', 'pending', 45000, ['CPU Intel Core i7-14700K' => 1, 'Mainboard B760M DDR5' => 1, 'RAM 32GB DDR5 6000MHz' => 2]],
            ['88 Trần Phú, Đà Nẵng', 'paid', 'shipping', 25000, ['SSD NVMe 1TB Gen4' => 1, 'RAM 32GB DDR5 6000MHz' => 1]],
        ];

        foreach ($orders as [$address, $isPaid, $status, $shippingFee, $items]) {
            $syncData = [];
            $totalPrice = 0;
            foreach ($items as $productName => $quantity) {
                $product = $products->get($productName);
                if (!$product) continue;
                $syncData[$product->id] = ['quantity' => $quantity, 'price' => $product->price];
                $totalPrice += $product->price * $quantity;
            }
            Order::updateOrCreate(
                ['user_id' => $user->id, 'address' => $address],
                ['is_paid' => $isPaid, 'status' => $status, 'shipping_fee' => $shippingFee, 'total_price' => $totalPrice]
            )->products()->sync($syncData);
        }
    }
}
