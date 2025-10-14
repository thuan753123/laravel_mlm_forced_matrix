<?php

return [
    'width' => (int) env('MLM_WIDTH', 10),
    'max_depth' => (int) env('MLM_MAX_DEPTH', 1),
    'commissions' => [
        1 => 0.10, // 10% cho tầng 1 (direct downline)
    ],
    'spillover_mode' => env('MLM_SPILLOVER', 'bfs'),
    'placement_mode' => env('MLM_PLACEMENT', 'forced'),
    'capping_per_cycle' => (int) env('MLM_CAP_CYCLE', 10_000_000), // VND
    'cycle_period' => env('MLM_CYCLE_PERIOD', 'weekly'), // daily|weekly|monthly
    'qualify_rules' => [
        'min_personal_volume' => 0,
        'active_order_days' => 30,
        'kyc_required' => false,
    ],
    'idempotency' => true,
];