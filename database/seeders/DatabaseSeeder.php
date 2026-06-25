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
            'PC Gaming' => 'https://ttgshop.vn/media/product/250_1071871333_13110_dsc00342_copy_e15810bfa2c74f2ea64d272cd24e9da0.jpg',
            'Linh kiện' => 'https://nguyencongpc.vn/media/product/250-25342-14700k.png',
        ];

        $downloaded = [];

        $products = collect();
        $productData = [
            // Laptop (10)
            ['Laptop ASUS Vivobook 14 OLED', 'Laptop mỏng nhẹ, màn hình OLED 14 inch 2.8K, phù hợp học tập và làm việc', 18990000, 20990000, 12, 24, ['Laptop']],
            ['Laptop Dell Inspiron 15', 'Laptop văn phòng bền bỉ, hiệu năng ổn định, pin dùng lâu', 16990000, null, 10, 24, ['Laptop']],
            ['Laptop Lenovo IdeaPad 5 15AMD', 'Laptop đa năng với CPU AMD Ryzen 7, pin trâu 8 tiếng', 15990000, 17990000, 10, 24, ['Laptop']],
            ['Laptop HP Pavilion 14 Plus', 'Laptop mỏng nhẹ thời trang, màn hình 2.8K OLED sắc nét', 18990000, null, 8, 24, ['Laptop']],
            ['Laptop Acer Swift Go 14', 'Laptop siêu nhẹ 1.3kg, pin cả ngày dài, cổng USB4', 16490000, 17990000, 12, 24, ['Laptop']],
            ['Laptop MSI Modern 14 C7M', 'Laptop học tập và làm việc cấu hình tốt, màn hình IPS Full HD', 14990000, null, 15, 24, ['Laptop']],
            ['Laptop ASUS TUF Gaming F15', 'Laptop gaming bền bỉ chuẩn quân đội, RTX 4050, màn hình 144Hz', 22990000, 25990000, 6, 24, ['Laptop']],
            ['Laptop Dell XPS 13 Plus', 'Laptop cao cấp siêu mỏng nhẹ, cảm ứng OLED 3.5K, i7 thế hệ 13', 35990000, null, 4, 24, ['Laptop']],
            ['Laptop Lenovo ThinkPad X1 Carbon Gen 11', 'Laptop doanh nhân cao cấp, bền bỉ chuẩn quân đội, bảo mật vân tay', 42990000, 45990000, 3, 36, ['Laptop']],
            ['Laptop HP Spectre x360 14', 'Laptop 2-trong-1 cảm ứng, thiết kế sang trọng, màn hình OLED 2.8K', 38990000, null, 5, 24, ['Laptop']],

            // PC Gaming (10)
            ['PC Gaming Ryzen 5 7500F + RTX 4060', 'Bộ PC gaming tầm trung cho 1080p và 2K, ray tracing tốt', 25990000, 27990000, 6, 36, ['PC Gaming']],
            ['PC Gaming Intel Core i5 + RTX 4070', 'Bộ máy chiến game và streaming mượt mà, 32GB RAM', 32990000, null, 4, 36, ['PC Gaming']],
            ['PC Gaming Ryzen 7 7800X3D + RTX 4080', 'Bộ PC gaming cao cấp chơi 4K mượt mà, 64GB RAM DDR5', 45990000, 49990000, 3, 36, ['PC Gaming']],
            ['PC Gaming Intel i7-14700K + RTX 4070 Ti', 'Bộ PC hiệu năng cao cho game thủ chuyên nghiệp và streamer', 42990000, null, 4, 36, ['PC Gaming']],
            ['PC Gaming Ryzen 5 5600 + RTX 3060', 'Bộ PC gaming giá rẻ, chiến game 1080p tốt, nguồn 650W', 18990000, 20990000, 8, 36, ['PC Gaming']],
            ['PC Gaming Intel i3-14100F + GTX 1650', 'Bộ PC văn phòng và game nhẹ tiết kiệm chi phí', 11990000, null, 10, 24, ['PC Gaming']],
            ['PC Gaming Ryzen 9 7950X + RTX 4090', 'Bộ PC workstation mạnh nhất cho render 3D, AI và game 8K', 89990000, null, 1, 36, ['PC Gaming']],
            ['PC Gaming Intel i9-14900K + RTX 4080 Super', 'Bộ PC gaming flagship thế hệ mới, tản nhiệt nước AIO 360mm', 65990000, 69990000, 2, 36, ['PC Gaming']],
            ['PC Gaming Ryzen 7 5700X + RX 7700 XT', 'Bộ PC gaming tầm trung mạnh mẽ, 16GB VRAM, FPS cao', 27990000, 29990000, 5, 36, ['PC Gaming']],
            ['PC Gaming Intel i5-14600KF + RTX 4060 Ti', 'Bộ PC gaming 2K ổn định, tản nhiệt khí hiệu quả, SSD 1TB', 31990000, null, 6, 36, ['PC Gaming']],

            // Màn hình (10)
            ['Màn hình LG 27inch IPS 2K 75Hz', 'Màn hình 27 inch hiển thị sắc nét, màu sắc chuẩn IPS', 4990000, 5590000, 18, 24, ['Màn hình']],
            ['Màn hình Samsung 24inch Full HD 100Hz', 'Màn hình gaming và làm việc giá tốt, viền mỏng', 3290000, null, 22, 24, ['Màn hình']],
            ['Màn hình Dell 27inch 4K IPS U2723QE', 'Màn hình 4K chuyên đồ họa, USB-C 90W tích hợp, chống lóa', 15990000, null, 10, 36, ['Màn hình']],
            ['Màn hình ASUS 27inch 2K 170Hz VG27AQ', 'Màn hình gaming 2K tần số quét cao, 1ms, G-Sync', 9990000, 10990000, 14, 36, ['Màn hình']],
            ['Màn hình AOC 24inch Full HD 144Hz 24G2', 'Màn hình gaming giá rẻ, 144Hz mượt, viền siêu mỏng', 5490000, 5990000, 20, 24, ['Màn hình']],
            ['Màn hình BenQ 27inch 2K PD2705Q Designer', 'Màn hình chuyên thiết kế, màu chuẩn Delta E, USB-C', 12990000, 13990000, 6, 36, ['Màn hình']],
            ['Màn hình Gigabyte 34inch Curved 2K M34WQ', 'Màn hình cong 34 inch siêu rộng, đa nhiệm xuất sắc', 14990000, null, 8, 36, ['Màn hình']],
            ['Màn hình MSI 27inch 2K 180Hz G274QPF', 'Màn hình gaming nhanh nhạy, 1ms response, Rapid IPS', 10490000, 11490000, 12, 36, ['Màn hình']],
            ['Màn hình ViewSonic 24inch Full HD IPS VX2476', 'Màn hình văn phòng IPS giá rẻ, tông màu trung tính', 3990000, null, 25, 24, ['Màn hình']],
            ['Màn hình LG 32inch 4K UHD 32UN650', 'Màn hình 4K lớn cho đồ họa và giải trí, IPS, HDR10', 13990000, 15990000, 7, 36, ['Màn hình']],

            // Linh kiện (10)
            ['CPU Intel Core i7-14700K', 'CPU hiệu năng cao cho gaming và đồ họa, 20 nhân 28 luồng', 11290000, null, 8, 36, ['Linh kiện']],
            ['CPU AMD Ryzen 7 7800X3D', 'CPU tối ưu cho chơi game, 3D V-Cache, hiệu suất mạnh', 14290000, 14990000, 7, 36, ['Linh kiện']],
            ['RAM 32GB DDR5 6000MHz', 'Bộ nhớ RAM dung lượng lớn cho đa nhiệm, tản nhiệt tốt', 2790000, null, 30, 36, ['Linh kiện']],
            ['Mainboard B760M DDR5', 'Bo mạch chủ hỗ trợ CPU Intel thế hệ mới, DDR5, PCIe 4.0', 3890000, 4290000, 16, 24, ['Linh kiện']],
            ['CPU Intel Core i5-14600K', 'CPU tầm trung hiệu năng cao, 14 nhân 20 luồng, ép xung được', 9490000, 9990000, 10, 36, ['Linh kiện']],
            ['RAM 16GB DDR4 3200MHz', 'RAM DDR4 phổ thông cho build máy tiết kiệm, đa nhiệm tốt', 890000, null, 35, 36, ['Linh kiện']],
            ['Mainboard Z790 DDR5 Gigabyte', 'Bo mạch chủ cao cấp support DDR5 và PCIe 5.0, WiFi tích hợp', 7990000, 8990000, 8, 24, ['Linh kiện']],
            ['VGA RTX 4060 Ti 8GB Gigabyte', 'Card đồ họa tầm trung, ray tracing, DLSS 3, 1080p-2K', 13990000, null, 9, 36, ['Linh kiện']],
            ['VGA RX 7700 XT 12GB PowerColor', 'Card đồ họa AMD tầm trung, 12GB VRAM, cạnh tranh giá', 12990000, 13990000, 7, 36, ['Linh kiện']],
            ['PSU 750W 80 Plus Gold Corsair', 'Nguồn máy tính cao cấp, modular, bảo vệ OVP/SCP/OPP', 2990000, 3490000, 18, 60, ['Linh kiện']],

            // Thiết bị ngoại vi (10)
            ['Chuột gaming Logitech G102', 'Chuột gaming phổ biến, độ nhạy 8000DPI, LED RGB', 490000, 590000, 45, 12, ['Thiết bị ngoại vi']],
            ['Bàn phím cơ Akko 3087', 'Bàn phím cơ layout gọn TKL, switch êm, LED RGB', 1490000, 1690000, 20, 12, ['Thiết bị ngoại vi']],
            ['Tai nghe gaming HyperX Cloud Stinger', 'Tai nghe gaming đeo thoải mái, mic rõ, âm thanh vòm 7.1', 1190000, 1390000, 14, 12, ['Thiết bị ngoại vi']],
            ['Chuột không dây Logitech MX Master 3S', 'Chuột văn phòng cao cấp, silent click, ergonomic, pin 70 ngày', 2490000, null, 15, 12, ['Thiết bị ngoại vi']],
            ['Bàn phím cơ Logitech G Pro X', 'Bàn phím cơ gaming hot-swap switch GX, RGB LIGHTSYNC', 3990000, 4490000, 10, 12, ['Thiết bị ngoại vi']],
            ['Tai nghe Sony WH-1000XM5', 'Tai nghe chống ồn chủ động cao cấp, 30h pin, Hi-Res Audio', 7990000, 8990000, 8, 12, ['Thiết bị ngoại vi']],
            ['Webcam Logitech C920', 'Webcam Full HD 1080p, tự động lấy nét, mic kép', 1990000, null, 20, 12, ['Thiết bị ngoại vi']],
            ['Lót chuột SteelSeries QcK Large', 'Lót chuột gaming kích thước 450x400mm, bề mặt vải kiểm soát tốt', 490000, 590000, 50, 6, ['Thiết bị ngoại vi']],
            ['Bàn phím không dây Apple Magic Keyboard', 'Bàn phím mỏng nhẹ cho hệ sinh thái Apple, pin sạc', 3490000, null, 6, 12, ['Thiết bị ngoại vi']],
            ['Tay cầm chơi game Xbox Wireless', 'Tay cầm chơi game không dây, kết nối Windows 10/11, rung', 1590000, 1790000, 12, 12, ['Thiết bị ngoại vi']],

            // Lưu trữ (10)
            ['SSD NVMe 1TB Gen4', 'Ổ cứng thể rắn NVMe PCIe 4.0, tốc độ đọc 7000MB/s', 1890000, 2190000, 28, 36, ['Lưu trữ']],
            ['SSD SATA 480GB Kingston', 'Ổ cứng thể rắn SATA III 480GB cho nâng cấp máy cũ', 990000, null, 25, 36, ['Lưu trữ']],
            ['HDD 2TB 7200rpm Seagate Barracuda', 'Ổ cứng cơ dung lượng lớn, tốc độ 7200vòng/phút, cache 256MB', 1590000, 1790000, 20, 36, ['Lưu trữ']],
            ['SSD NVMe 500GB Gen3 Samsung 980', 'SSD NVMe tốc độ cao PCIe 3.0, đọc 3500MB/s, DRAM', 1590000, null, 22, 60, ['Lưu trữ']],
            ['SSD NVMe 2TB Gen4 Samsung 990 Pro', 'SSD NVMe cao cấp PCIe 4.0, đọc 7450MB/s, viết 6900MB/s', 5990000, 6990000, 6, 60, ['Lưu trữ']],
            ['HDD 1TB 5400rpm WD Blue', 'Ổ cứng lưu trữ dữ liệu cơ bản, ổn định, tiết kiệm điện', 890000, 990000, 30, 36, ['Lưu trữ']],
            ['SSD Portable 1TB USB-C Samsung T7', 'SSD di động nhỏ gọn, tốc độ 1050MB/s, chống sốc', 3490000, null, 10, 24, ['Lưu trữ']],
            ['Thẻ nhớ microSD 128GB SanDisk Extreme', 'Thẻ nhớ microSDXC tốc độ cao 170MB/s, A2, cho 4K', 590000, 690000, 40, 12, ['Lưu trữ']],
            ['USB 3.0 64GB Kingston DataTraveler', 'USB 3.0 dung lượng 64GB, tương thích ngược USB 2.0', 290000, null, 50, 12, ['Lưu trữ']],
            ['NAS 2-Bay Synology DS223j', 'Máy chủ lưu trữ mạng 2 ổ, quản lý qua app, RAID tự động', 7990000, null, 4, 36, ['Lưu trữ']],
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
