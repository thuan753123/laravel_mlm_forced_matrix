<?php

if (!function_exists('formatMoney')) {
    /**
     * Format số tiền với đơn vị triệu/tỷ
     * 
     * @param float $amount Số tiền (đơn vị triệu)
     * @return string
     */
    function formatMoney(float $amount): string
    {
        if ($amount >= 1000) {
            $billions = $amount / 1000;
            // Loại bỏ .00 nếu là số nguyên
            if ($billions == floor($billions)) {
                return number_format($billions, 0) . ' tỷ';
            }
            return number_format($billions, 2) . ' tỷ';
        }
        return number_format($amount, 0) . ' triệu';
    }
}

