<?php

namespace Database\Seeders;

use App\Models\DiscountTier;
use Illuminate\Database\Seeder;

class DiscountTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        DiscountTier::truncate();

        // Bậc 1: ≤ 100 triệu, 25% - NPP mới
        DiscountTier::create([
            'tier' => 1,
            'revenue_from' => 0,
            'revenue_to' => 100,
            'applicable_revenue_from' => 0,
            'applicable_revenue_to' => 100,
            'discount_rate' => 25,
            'tier_name' => 'NPP mới',
            'note' => 'Áp dụng cho NPP mới',
            'is_active' => true,
        ]);

        // Bậc 2: Từ 101 đến 1000 triệu, 30% - NPP tiêu chuẩn  
        DiscountTier::create([
            'tier' => 2,
            'revenue_from' => 101,
            'revenue_to' => 1000,
            'applicable_revenue_from' => 100, // Bắt đầu sau 100 triệu
            'applicable_revenue_to' => 1000,
            'discount_rate' => 30,
            'tier_name' => 'NPP tiêu chuẩn',
            'note' => 'NPP tiêu chuẩn',
            'is_active' => true,
        ]);

        // Bậc 3: Từ 1,001 đến 1,199 triệu - NPP cao cấp
        // Chỉ áp dụng 35% cho phần từ 1000 trở lên
        DiscountTier::create([
            'tier' => 3,
            'revenue_from' => 1001,
            'revenue_to' => 1199,
            'applicable_revenue_from' => 1000,
            'applicable_revenue_to' => 1199,
            'discount_rate' => 35,
            'tier_name' => 'NPP cao cấp',
            'note' => 'NPP cao cấp',
            'is_active' => true,
        ]);

        // Bậc 4: Từ 1,200 trở lên, 35% - NPP VIP
        DiscountTier::create([
            'tier' => 4,
            'revenue_from' => 1200,
            'revenue_to' => null, // Không giới hạn
            'applicable_revenue_from' => 1200,
            'applicable_revenue_to' => null,
            'discount_rate' => 35,
            'tier_name' => 'NPP VIP',
            'note' => 'NPP VIP',
            'is_active' => true,
        ]);
    }
}

