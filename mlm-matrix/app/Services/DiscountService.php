<?php

namespace App\Services;

use App\Models\DiscountTier;
use Illuminate\Support\Collection;

class DiscountService
{
    /**
     * Tính chiết khấu dựa trên doanh thu
     * Công thức: Chiết khấu tháng = Σ(Doanh thu từng bậc × Tỷ lệ chiết khấu tương ứng)
     *
     * @param float $revenue Doanh thu (triệu đồng)
     * @return array ['total_discount' => float, 'breakdown' => array]
     */
    public function calculateDiscount(float $revenue): array
    {
        $tiers = DiscountTier::active()->get();
        $breakdown = [];
        $totalDiscount = 0;

        // Xử lý từng bậc chiết khấu
        foreach ($tiers as $tier) {
            // Kiểm tra xem doanh thu có rơi vào bậc này không
            if ($this->isRevenueInTier($revenue, $tier)) {
                $applicableRevenue = $this->calculateApplicableRevenue($revenue, $tier);
                
                if ($applicableRevenue > 0) {
                    $discount = $applicableRevenue * ($tier->discount_rate / 100);
                    $totalDiscount += $discount;
                    
                    $breakdown[] = [
                        'tier' => $tier->tier,
                        'tier_name' => $tier->tier_name,
                        'applicable_revenue' => $applicableRevenue,
                        'discount_rate' => $tier->discount_rate,
                        'discount_amount' => $discount,
                        'note' => $tier->note,
                    ];
                }
            }
        }

        // Tính tỷ lệ phần trăm chiết khấu
        $discountPercentage = $revenue > 0 ? ($totalDiscount / $revenue) * 100 : 0;

        return [
            'total_revenue' => $revenue,
            'total_discount' => round($totalDiscount, 2),
            'discount_percentage' => round($discountPercentage, 2),
            'breakdown' => $breakdown,
        ];
    }

    /**
     * Kiểm tra doanh thu có đủ để áp dụng bậc này không
     * Logic lũy tiến: Doanh thu phải >= revenue_from của tier
     * Ví dụ: DT 1100 sẽ áp dụng cho bậc 1 (0-100), bậc 2 (101-1000), bậc 3 (1001-1199)
     */
    private function isRevenueInTier(float $revenue, DiscountTier $tier): bool
    {
        // Chỉ cần revenue >= revenue_from là áp dụng
        return $revenue >= $tier->revenue_from;
    }

    /**
     * Tính doanh thu áp dụng cho bậc chiết khấu
     */
    private function calculateApplicableRevenue(float $revenue, DiscountTier $tier): float
    {
        $applicableRevenue = 0;

        // Nếu doanh thu nhỏ hơn applicable_revenue_from thì không áp dụng
        if ($revenue < $tier->applicable_revenue_from) {
            return 0;
        }

        // Tính doanh thu áp dụng
        $start = $tier->applicable_revenue_from;
        $end = $tier->applicable_revenue_to ?? $revenue;

        // Doanh thu áp dụng = min(revenue, end) - start
        $applicableRevenue = min($revenue, $end) - $start;

        // Đảm bảo không âm
        return max(0, $applicableRevenue);
    }

    /**
     * Lấy thông tin bậc chiết khấu dựa trên doanh thu
     */
    public function getTierInfo(float $revenue): ?array
    {
        $tiers = DiscountTier::active()->orderBy('tier', 'desc')->get();

        foreach ($tiers as $tier) {
            if ($revenue >= $tier->revenue_from && ($tier->revenue_to === null || $revenue <= $tier->revenue_to)) {
                return [
                    'tier' => $tier->tier,
                    'tier_name' => $tier->tier_name,
                    'revenue_from' => $tier->revenue_from,
                    'revenue_to' => $tier->revenue_to,
                    'discount_rate' => $tier->discount_rate,
                    'note' => $tier->note,
                ];
            }
        }

        return null;
    }

    /**
     * Lấy tất cả các bậc chiết khấu đang active
     */
    public function getAllTiers(): Collection
    {
        return DiscountTier::active()->get();
    }

    /**
     * Tính doanh thu cần đạt để lên bậc tiếp theo
     */
    public function getNextTierTarget(float $currentRevenue): ?array
    {
        $tiers = DiscountTier::active()->get();
        
        foreach ($tiers as $tier) {
            if ($currentRevenue < $tier->revenue_from) {
                $revenueNeeded = $tier->revenue_from - $currentRevenue;
                $progress = $currentRevenue > 0 ? ($currentRevenue / $tier->revenue_from) * 100 : 0;
                
                return [
                    'next_tier' => [
                        'tier' => $tier->tier,
                        'tier_name' => $tier->tier_name,
                        'discount_rate' => $tier->discount_rate,
                        'revenue_from' => $tier->revenue_from,
                        'revenue_to' => $tier->revenue_to,
                    ],
                    'revenue_needed' => $revenueNeeded,
                    'target_revenue' => $tier->revenue_from,
                    'progress' => round($progress, 2),
                ];
            }
        }

        return null; // Đã ở bậc cao nhất
    }

    /**
     * Ví dụ tính chiết khấu với các mức doanh thu khác nhau
     */
    public function getExamples(): array
    {
        $examples = [
            50,      // Bậc 1
            100,     // Bậc 1
            500,     // Bậc 2
            1000,    // Bậc 2
            1100,    // Bậc 3
            1500,    // Bậc 4
        ];

        $results = [];
        foreach ($examples as $revenue) {
            $results[] = $this->calculateDiscount($revenue);
        }

        return $results;
    }

    /**
     * Format số tiền với đơn vị triệu/tỷ
     */
    public static function formatMoney(float $amount): string
    {
        if ($amount >= 1000) {
            $billions = $amount / 1000;
            return number_format($billions, 2) . ' tỷ';
        }
        return number_format($amount, 0) . ' triệu';
    }
}

