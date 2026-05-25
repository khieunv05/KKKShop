<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ComponentProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $componentCategoryId = Category::where('name', 'Linh kiện máy tính')->value('id');

        $components = [
            [
                'name' => 'AMD Ryzen 5 5600',
                'description' => 'CPU 6 nhân 12 luồng, xung nhịp tốt cho gaming phổ thông.',
                'price' => 2990000,
                'stock' => 30,
                'warranty' => '36 tháng',
                'image' => 'images/components/ryzen-5-5600.jpg',
            ],
            [
                'name' => 'Intel Core i5-13400F',
                'description' => 'CPU 10 nhân, hiệu năng tốt cho gaming và làm việc đa nhiệm.',
                'price' => 3890000,
                'stock' => 26,
                'warranty' => '36 tháng',
                'image' => 'images/components/i5-13400f.jpg',
            ],
            [
                'name' => 'Intel Core i7-14700F',
                'description' => 'CPU hiệu năng cao cho workstation và gaming nặng.',
                'price' => 9490000,
                'stock' => 14,
                'warranty' => '36 tháng',
                'image' => 'images/components/i7-14700f.jpg',
            ],
            [
                'name' => 'AMD Ryzen 5 7500F',
                'description' => 'CPU AM5 6 nhân, rất phù hợp cho máy AMD gaming tầm trung.',
                'price' => 4390000,
                'stock' => 22,
                'warranty' => '36 tháng',
                'image' => 'images/components/ryzen-5-7500f.jpg',
            ],
            [
                'name' => 'AMD Ryzen 7 7800X3D',
                'description' => 'CPU gaming hàng đầu với 3D V-Cache, tối ưu FPS.',
                'price' => 10290000,
                'stock' => 10,
                'warranty' => '36 tháng',
                'image' => 'images/components/ryzen-7-7800x3d.jpg',
            ],
            [
                'name' => 'AMD Ryzen 9 7900',
                'description' => 'CPU 12 nhân 24 luồng cho dựng phim, render và đa nhiệm nặng.',
                'price' => 10990000,
                'stock' => 8,
                'warranty' => '36 tháng',
                'image' => 'images/components/ryzen-9-7900.jpg',
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4060 8GB',
                'description' => 'Card đồ họa phổ thông hỗ trợ DLSS 3, phù hợp 1080p.',
                'price' => 7990000,
                'stock' => 20,
                'warranty' => '36 tháng',
                'image' => 'images/components/rtx-4060.jpg',
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4060 Ti 8GB',
                'description' => 'Card đồ họa tầm trung cho gaming 1080p/1440p.',
                'price' => 10290000,
                'stock' => 16,
                'warranty' => '36 tháng',
                'image' => 'images/components/rtx-4060-ti.jpg',
            ],
            [
                'name' => 'NVIDIA GeForce RTX 4070 SUPER 12GB',
                'description' => 'GPU mạnh cho gaming 1440p và làm đồ họa.',
                'price' => 17990000,
                'stock' => 12,
                'warranty' => '36 tháng',
                'image' => 'images/components/rtx-4070-super.jpg',
            ],
            [
                'name' => 'AMD Radeon RX 7700 XT 12GB',
                'description' => 'Card AMD tối ưu hiệu năng trên giá thành cho gaming.',
                'price' => 11990000,
                'stock' => 15,
                'warranty' => '36 tháng',
                'image' => 'images/components/rx-7700-xt.jpg',
            ],
            [
                'name' => 'AMD Radeon RX 7800 XT 16GB',
                'description' => 'GPU AMD cao cấp cho gaming 1440p và 4K entry.',
                'price' => 15990000,
                'stock' => 11,
                'warranty' => '36 tháng',
                'image' => 'images/components/rx-7800-xt.jpg',
            ],
            [
                'name' => 'MSI PRO B550M-VC WIFI',
                'description' => 'Mainboard AM4 với Wi-Fi tích hợp, hợp cho Ryzen 5000.',
                'price' => 2890000,
                'stock' => 24,
                'warranty' => '36 tháng',
                'image' => 'images/components/msi-b550m-vc-wifi.jpg',
            ],
            [
                'name' => 'MSI PRO B760M-A WIFI DDR4',
                'description' => 'Mainboard LGA1700 hỗ trợ DDR4, phù hợp Core i5.',
                'price' => 3490000,
                'stock' => 19,
                'warranty' => '36 tháng',
                'image' => 'images/components/msi-b760m-a-wifi-ddr4.jpg',
            ],
            [
                'name' => 'MSI B650M MORTAR WIFI',
                'description' => 'Mainboard AM5 mạnh mẽ cho Ryzen 7000.',
                'price' => 5790000,
                'stock' => 13,
                'warranty' => '36 tháng',
                'image' => 'images/components/msi-b650m-mortar-wifi.jpg',
            ],
            [
                'name' => 'Kingston Fury Beast 16GB DDR4-3200',
                'description' => 'RAM 16GB 2x8GB, tối ưu gaming phổ thông.',
                'price' => 1090000,
                'stock' => 40,
                'warranty' => '36 tháng',
                'image' => 'images/components/kingston-fury-beast-16gb-ddr4.jpg',
            ],
            [
                'name' => 'Corsair Vengeance 32GB DDR5-6000',
                'description' => 'Bộ RAM 32GB DDR5 cho workstation và gaming cao cấp.',
                'price' => 2890000,
                'stock' => 28,
                'warranty' => '36 tháng',
                'image' => 'images/components/corsair-vengeance-32gb-ddr5.jpg',
            ],
            [
                'name' => 'Samsung 970 EVO Plus 1TB',
                'description' => 'SSD NVMe PCIe 3.0 1TB ổn định, tốc độ cao.',
                'price' => 1890000,
                'stock' => 35,
                'warranty' => '60 tháng',
                'image' => 'images/components/samsung-970-evo-plus-1tb.jpg',
            ],
            [
                'name' => 'Kingston NV2 1TB',
                'description' => 'SSD NVMe 1TB phổ thông, giá tốt cho máy văn phòng.',
                'price' => 1390000,
                'stock' => 38,
                'warranty' => '36 tháng',
                'image' => 'images/components/kingston-nv2-1tb.jpg',
            ],
            [
                'name' => 'Corsair CV650 650W',
                'description' => 'Nguồn 650W 80 Plus Bronze cho dàn gaming tầm trung.',
                'price' => 1490000,
                'stock' => 25,
                'warranty' => '36 tháng',
                'image' => 'images/components/corsair-cv650.jpg',
            ],
            [
                'name' => 'DeepCool AK400',
                'description' => 'Tản khí hiệu quả, yên tĩnh cho CPU phổ thông đến tầm trung.',
                'price' => 790000,
                'stock' => 32,
                'warranty' => '24 tháng',
                'image' => 'images/components/deepcool-ak400.jpg',
            ],
            [
                'name' => 'NZXT H5 Flow',
                'description' => 'Vỏ case airflow tốt, dễ build cho gaming và workstation.',
                'price' => 2790000,
                'stock' => 18,
                'warranty' => '24 tháng',
                'image' => 'images/components/nzxt-h5-flow.jpg',
            ],
        ];

        foreach ($components as $componentData) {
            $component = Product::updateOrCreate(
                ['name' => $componentData['name']],
                array_merge($componentData, [
                    'old_price' => null,
                    'is_active' => true,
                    'is_builder' => false,
                ])
            );

            if ($componentCategoryId) {
                $component->categories()->syncWithoutDetaching([$componentCategoryId]);
            }
        }

        $builds = [
            'KKK Gaming Ryzen 5 5600 RTX 4060' => [
                ['AMD Ryzen 5 5600', 1],
                ['MSI PRO B550M-VC WIFI', 1],
                ['NVIDIA GeForce RTX 4060 8GB', 1],
                ['Kingston Fury Beast 16GB DDR4-3200', 1],
                ['Samsung 970 EVO Plus 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['DeepCool AK400', 1],
                ['NZXT H5 Flow', 1],
            ],
            'KKK Gaming Core i5 13400F RTX 4060 Ti' => [
                ['Intel Core i5-13400F', 1],
                ['MSI PRO B760M-A WIFI DDR4', 1],
                ['NVIDIA GeForce RTX 4060 Ti 8GB', 1],
                ['Kingston Fury Beast 16GB DDR4-3200', 1],
                ['Kingston NV2 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['DeepCool AK400', 1],
                ['NZXT H5 Flow', 1],
            ],
            'KKK Workstation Core i7 14700F RTX 4070 SUPER' => [
                ['Intel Core i7-14700F', 1],
                ['MSI PRO B760M-A WIFI DDR4', 1],
                ['NVIDIA GeForce RTX 4070 SUPER 12GB', 1],
                ['Corsair Vengeance 32GB DDR5-6000', 1],
                ['Samsung 970 EVO Plus 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['NZXT H5 Flow', 1],
            ],
            'KKK Creator Ryzen 9 7900 RTX 4070 SUPER' => [
                ['AMD Ryzen 9 7900', 1],
                ['MSI B650M MORTAR WIFI', 1],
                ['NVIDIA GeForce RTX 4070 SUPER 12GB', 1],
                ['Corsair Vengeance 32GB DDR5-6000', 1],
                ['Samsung 970 EVO Plus 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['NZXT H5 Flow', 1],
            ],
            'KKK AMD Ryzen 5 7500F RX 7700 XT' => [
                ['AMD Ryzen 5 7500F', 1],
                ['MSI B650M MORTAR WIFI', 1],
                ['AMD Radeon RX 7700 XT 12GB', 1],
                ['Corsair Vengeance 32GB DDR5-6000', 1],
                ['Kingston NV2 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['DeepCool AK400', 1],
            ],
            'KKK AMD Ryzen 7 7800X3D RX 7800 XT' => [
                ['AMD Ryzen 7 7800X3D', 1],
                ['MSI B650M MORTAR WIFI', 1],
                ['AMD Radeon RX 7800 XT 16GB', 1],
                ['Corsair Vengeance 32GB DDR5-6000', 1],
                ['Samsung 970 EVO Plus 1TB', 1],
                ['Corsair CV650 650W', 1],
                ['NZXT H5 Flow', 1],
            ],
            'ASUS NUC 13 Pro Mini PC' => [
                ['Kingston NV2 1TB', 1],
                ['Kingston Fury Beast 16GB DDR4-3200', 1],
            ],
            'Minisforum UM790 Pro Mini PC' => [
                ['Corsair Vengeance 32GB DDR5-6000', 1],
                ['Samsung 970 EVO Plus 1TB', 1],
            ],
            'Dell OptiPlex 7010 Micro' => [
                ['Kingston NV2 1TB', 1],
                ['Kingston Fury Beast 16GB DDR4-3200', 1],
            ],
            'HP ProDesk 400 G9 SFF' => [
                ['Kingston NV2 1TB', 1],
                ['Kingston Fury Beast 16GB DDR4-3200', 1],
            ],
        ];

        foreach ($builds as $parentName => $items) {
            $parentId = Product::where('name', $parentName)->value('id');

            foreach ($items as [$childName, $quantity]) {
                $childId = Product::where('name', $childName)->value('id');

                if ($parentId && $childId) {
                    DB::table('component_product')->updateOrInsert(
                        [
                            'parent_id' => $parentId,
                            'child_id' => $childId,
                        ],
                        [
                            'quantity' => $quantity,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
