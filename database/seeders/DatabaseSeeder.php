<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $categoryData = [
            'Laptop' => 'Laptop văn phòng, học tập và gaming',
            'PC Gaming' => 'Bộ PC chơi game hiệu năng cao',
            'Màn hình' => 'Màn hình cho làm việc và giải trí',
            'Linh kiện' => 'Linh kiện nâng cấp máy tính',
            'Thiết bị ngoại vi' => 'Chuột, bàn phím, tai nghe và phụ kiện',
            'Lưu trữ' => 'SSD, HDD và thiết bị lưu trữ',
        ];

        foreach ($categoryData as $name => $description) {
            DB::table('categories')->updateOrInsert(
                ['name' => $name],
                ['description' => $description]
            );
        }

        $categories = DB::table('categories')->pluck('id', 'name');

        $categoryImages = [
            'Laptop'              => 'https://nguyencongpc.vn/media/product/250-23966-laptop-msi-modern-14-c7m-205xv-01-638607589205347493.jpg',
            'PC Gaming'           => 'https://ttgshop.vn/media/product/250_1071871333_13110_dsc00342_copy_e15810bfa2c74f2ea64d272cd24e9da0.jpg',
            'Màn hình'            => 'https://nguyencongpc.vn/media/product/250-26511-man-hinh-lg-27gp850-b-27-2k-165hz-1-638472275878912689.jpg',
            'Linh kiện'           => 'https://nguyencongpc.vn/media/product/250-25342-14700k.png',
            'Thiết bị ngoại vi'   => 'https://nguyencongpc.vn/media/product/250-17050-akko-3087-black-gold.jpg',
            'Lưu trữ'             => 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/product/1/t/1tb_1.png',
        ];

        $downloaded = [];

        $products = collect();
        $productData = [
            ['Laptop ASUS Vivobook 14 OLED', 'Laptop mỏng nhẹ, màn hình OLED, phù hợp học tập và làm việc', 18990000, 20990000, 12, 24, ['Laptop']],
            ['Laptop Dell Inspiron 15', 'Laptop văn phòng bền bỉ, hiệu năng ổn định', 16990000, null, 10, 24, ['Laptop']],
            ['PC Gaming Ryzen 5 7500F + RTX 4060', 'Bộ PC gaming tầm trung cho 1080p và 2K', 25990000, 27990000, 6, 36, ['PC Gaming']],
            ['PC Gaming Intel Core i5 + RTX 4070', 'Bộ máy chiến game và streaming mượt mà', 32990000, null, 4, 36, ['PC Gaming']],
            ['Màn hình LG 27inch IPS 2K 75Hz', 'Màn hình 27 inch hiển thị sắc nét, màu sắc chuẩn', 4990000, 5590000, 18, 24, ['Màn hình']],
            ['Màn hình Samsung 24inch Full HD 100Hz', 'Màn hình gaming và làm việc giá tốt', 3290000, null, 22, 24, ['Màn hình']],
            ['CPU Intel Core i7-14700K', 'CPU hiệu năng cao cho gaming và đồ họa', 11290000, null, 8, 36, ['Linh kiện']],
            ['CPU AMD Ryzen 7 7800X3D', 'CPU tối ưu cho chơi game, hiệu suất mạnh', 14290000, 14990000, 7, 36, ['Linh kiện']],
            ['RAM 32GB DDR5 6000MHz', 'Bộ nhớ RAM dung lượng lớn cho đa nhiệm', 2790000, null, 30, 36, ['Linh kiện']],
            ['Mainboard B760M DDR5', 'Bo mạch chủ hỗ trợ CPU Intel thế hệ mới', 3890000, 4290000, 16, 24, ['Linh kiện']],
            ['SSD NVMe 1TB Gen4', 'Ổ cứng tốc độ cao cho hệ điều hành và game', 1890000, 2190000, 28, 36, ['Lưu trữ']],
            ['Chuột gaming Logitech G102', 'Chuột gaming phổ biến, độ nhạy tốt', 490000, 590000, 45, 12, ['Thiết bị ngoại vi']],
            ['Bàn phím cơ Akko 3087', 'Bàn phím cơ layout gọn, switch êm', 1490000, 1690000, 20, 12, ['Thiết bị ngoại vi']],
            ['Tai nghe gaming HyperX Cloud Stinger', 'Tai nghe gaming đeo thoải mái, mic rõ', 1190000, 1390000, 14, 12, ['Thiết bị ngoại vi']],
        ];

        foreach ($productData as [$name, $description, $price, $oldPrice, $stock, $warranty, $catNames]) {
            $imagePath = null;
            foreach ($catNames as $catName) {
                if (!isset($downloaded[$catName]) && isset($categoryImages[$catName])) {
                    try {
                        $ext = pathinfo(parse_url($categoryImages[$catName], PHP_URL_PATH), PATHINFO_EXTENSION);
                        $ext = $ext ?: 'jpg';
                        $filename = 'products/' . Str::slug($catName) . '.' . $ext;
                        $response = Http::timeout(10)->get($categoryImages[$catName]);
                        if ($response->successful()) {
                            Storage::disk('public')->put($filename, $response->body());
                            $downloaded[$catName] = $filename;
                        }
                    } catch (\Exception $e) {
                        $downloaded[$catName] = null;
                    }
                }
                if (isset($downloaded[$catName])) {
                    $imagePath = $downloaded[$catName];
                }
            }

            $product = Product::updateOrCreate(
                ['name' => $name],
                ['description' => $description, 'price' => $price, 'old_price' => $oldPrice, 'stock' => $stock, 'warranty' => $warranty, 'image' => $imagePath, 'is_active' => true]
            );
            $products->put($name, $product);
            foreach ($catNames as $catName) {
                $categoryId = $categories->get($catName);
                if ($categoryId) {
                    $product->categories()->syncWithoutDetaching([$categoryId]);
                }
            }
        }

        $orders = [
            ['Nguyễn Văn A', '0901111222', '123 Đường ABC, Hà Nội', 'cod', 'paid', 'completed', 30000, ['PC Gaming Ryzen 5 7500F + RTX 4060' => 1, 'Chuột gaming Logitech G102' => 2]],
            ['Trần Thị B', '0902222333', '45 Nguyễn Huệ, Hồ Chí Minh', 'bank_transfer', 'unpaid', 'pending', 45000, ['Laptop ASUS Vivobook 14 OLED' => 1, 'Tai nghe gaming HyperX Cloud Stinger' => 1]],
            ['Lê Văn C', '0903333444', '88 Trần Phú, Đà Nẵng', 'momo', 'paid', 'shipping', 25000, ['Màn hình LG 27inch IPS 2K 75Hz' => 1, 'SSD NVMe 1TB Gen4' => 1]],
        ];

        foreach ($orders as [$receiverName, $phone, $address, $paymentMethod, $isPaid, $status, $shippingFee, $items]) {
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
                [
                    'receiver_name' => $receiverName,
                    'phone' => $phone,
                    'payment_method' => $paymentMethod,
                    'is_paid' => $isPaid,
                    'status' => $status,
                    'shipping_fee' => $shippingFee,
                    'total_price' => $totalPrice,
                ]
            )->products()->sync($syncData);
        }
    }
}
