<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'KKK Gaming Ryzen 5 5600 RTX 4060',
                'description' => 'PC gaming phổ thông với Ryzen 5 5600, RTX 4060 và 16GB RAM.',
                'price' => 21990000,
                'old_price' => 23990000,
                'stock' => 12,
                'warranty' => '24 tháng',
                'image' => 'images/products/kkk-gaming-5600-4060.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Gaming', 'Linh kiện máy tính'],
            ],
            [
                'name' => 'KKK Gaming Core i5 13400F RTX 4060 Ti',
                'description' => 'Dàn máy chiến game 1080p/1440p với Core i5-13400F và RTX 4060 Ti.',
                'price' => 26990000,
                'old_price' => 28990000,
                'stock' => 8,
                'warranty' => '24 tháng',
                'image' => 'images/products/kkk-gaming-13400f-4060ti.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Gaming'],
            ],
            [
                'name' => 'KKK Workstation Core i7 14700F RTX 4070 SUPER',
                'description' => 'Máy trạm mạnh cho render, dựng phim và xử lý đồ họa nặng.',
                'price' => 41990000,
                'old_price' => 44990000,
                'stock' => 10,
                'warranty' => '36 tháng',
                'image' => 'images/products/kkk-workstation-14700f-4070s.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Workstation'],
            ],
            [
                'name' => 'KKK Creator Ryzen 9 7900 RTX 4070 SUPER',
                'description' => 'Cấu hình sáng tạo nội dung với Ryzen 9 7900 và RTX 4070 SUPER.',
                'price' => 48990000,
                'old_price' => 51990000,
                'stock' => 20,
                'warranty' => '36 tháng',
                'image' => 'images/products/kkk-creator-7900-4070s.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Workstation'],
            ],
            [
                'name' => 'KKK AMD Ryzen 5 7500F RX 7700 XT',
                'description' => 'PC AMD gaming cân bằng giữa hiệu năng và giá thành.',
                'price' => 28990000,
                'old_price' => 30990000,
                'stock' => 11,
                'warranty' => '24 tháng',
                'image' => 'images/products/kkk-amd-7500f-7700xt.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC AMD Gaming'],
            ],
            [
                'name' => 'KKK AMD Ryzen 7 7800X3D RX 7800 XT',
                'description' => 'Dàn AMD cao cấp tối ưu cho gaming FPS cao và stream.',
                'price' => 39990000,
                'old_price' => 42990000,
                'stock' => 7,
                'warranty' => '36 tháng',
                'image' => 'images/products/kkk-amd-7800x3d-7800xt.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC AMD Gaming'],
            ],
            [
                'name' => 'ASUS NUC 13 Pro Mini PC',
                'description' => 'Mini PC nhỏ gọn, phù hợp văn phòng và treo máy liên tục.',
                'price' => 14990000,
                'old_price' => null,
                'stock' => 15,
                'warranty' => '24 tháng',
                'image' => 'images/products/asus-nuc-13-pro.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Mini'],
            ],
            [
                'name' => 'Minisforum UM790 Pro Mini PC',
                'description' => 'Mini PC dùng Ryzen 9 7940HS, phù hợp làm việc đa nhiệm.',
                'price' => 16990000,
                'old_price' => 17990000,
                'stock' => 9,
                'warranty' => '24 tháng',
                'image' => 'images/products/minisforum-um790-pro.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Mini'],
            ],
            [
                'name' => 'Dell OptiPlex 7010 Micro',
                'description' => 'Máy văn phòng micro form factor cho doanh nghiệp và quầy giao dịch.',
                'price' => 11990000,
                'old_price' => 12990000,
                'stock' => 18,
                'warranty' => '12 tháng',
                'image' => 'images/products/dell-optiplex-7010-micro.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Văn Phòng'],
            ],
            [
                'name' => 'HP ProDesk 400 G9 SFF',
                'description' => 'Desktop SFF ổn định cho môi trường văn phòng và học tập.',
                'price' => 13490000,
                'old_price' => 14490000,
                'stock' => 14,
                'warranty' => '12 tháng',
                'image' => 'images/products/hp-prodesk-400-g9-sff.jpg',
                'is_active' => true,
                'is_builder' => false,
                'categories' => ['PC Văn Phòng'],
            ],
        ];

        foreach ($products as $productData) {
            $categoryNames = $productData['categories'];
            unset($productData['categories']);

            $product = Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );

            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id')
                ->all();

            $product->categories()->sync($categoryIds);
        }
    }
}
