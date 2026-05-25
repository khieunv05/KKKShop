<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'PC Gaming',
                'description' => 'Dàn máy tối ưu cho chơi game, hiệu năng cao.',
            ],
            [
                'name' => 'PC Workstation',
                'description' => 'Máy trạm phục vụ đồ họa, render và công việc chuyên nghiệp.',
            ],
            [
                'name' => 'PC AMD Gaming',
                'description' => 'PC gaming sử dụng nền tảng AMD.',
            ],
            [
                'name' => 'PC Mini',
                'description' => 'Máy tính nhỏ gọn, tiết kiệm diện tích.',
            ],
            [
                'name' => 'PC Văn Phòng',
                'description' => 'Cấu hình ổn định cho nhu cầu làm việc văn phòng.',
            ],
            [
                'name' => 'Linh kiện máy tính',
                'description' => 'CPU, RAM, SSD, VGA và các linh kiện rời.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
