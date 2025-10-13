<?php

return [
    'width' => (int) env('MLM_WIDTH', 2),
    'max_depth' => (int) env('MLM_MAX_DEPTH', 5),
    'commissions' => [
        1 => 0.10, // 10%
        2 => 0.05, // 5%
        3 => 0.03, // 3%
        4 => 0.02, // 2%
        5 => 0.01, // 1%
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