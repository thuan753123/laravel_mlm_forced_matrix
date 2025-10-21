@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">{{ __('ui.nav.dashboard') }}</h1>
                
                @auth

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">{{ __('ui.orders_page.heading') }}</h3>
                            <p class="text-gray-600 mb-4">Quản lý đơn hàng và thanh toán</p>
                            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.orders_page.create') }}
                            </a>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">{{ __('ui.matrix_page.heading') }}</h3>
                            <p class="text-gray-600 mb-4">Xem cây ma trận và thống kê</p>
                            <a href="{{ route('matrix.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.matrix_page.visualization') }}
                            </a>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">{{ __('ui.comm_page.heading') }}</h3>
                            <p class="text-gray-600 mb-4">Xem chiết khấu và lịch sử</p>
                            <a href="{{ route('discounts.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.comm_page.summary') }}
                            </a>
                        </div>
                    </div>

                    <!-- Statistics Charts Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <!-- Referral Statistics Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold">Thống Kê Người Giới Thiệu</h3>
                                <div class="flex gap-2">
                                    <button onclick="updateReferralPeriod('day')" id="ref-btn-day" class="px-3 py-1 text-sm rounded bg-indigo-600 text-white">Ngày</button>
                                    <button onclick="updateReferralPeriod('week')" id="ref-btn-week" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700">Tuần</button>
                                    <button onclick="updateReferralPeriod('month')" id="ref-btn-month" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700">Tháng</button>
                                </div>
                            </div>
                            <div class="relative" style="height: 300px;">
                                <canvas id="referralChart"></canvas>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 bg-blue-50 rounded">
                                    <p class="text-xs text-gray-600">Tổng số</p>
                                    <p class="text-xl font-bold text-blue-600" id="ref-chart-total">0</p>
                                </div>
                                <div class="p-2 bg-green-50 rounded">
                                    <p class="text-xs text-gray-600">TB/kỳ</p>
                                    <p class="text-xl font-bold text-green-600" id="ref-chart-avg">0</p>
                                </div>
                                <div class="p-2 bg-purple-50 rounded">
                                    <p class="text-xs text-gray-600">Cao nhất</p>
                                    <p class="text-xl font-bold text-purple-600" id="ref-chart-max">0</p>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Statistics Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold">Thống Kê Doanh Thu Giới Thiệu</h3>
                                <div class="flex gap-2">
                                    <button onclick="updateRevenuePeriod('day')" id="rev-btn-day" class="px-3 py-1 text-sm rounded bg-green-600 text-white">Ngày</button>
                                    <button onclick="updateRevenuePeriod('week')" id="rev-btn-week" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700">Tuần</button>
                                    <button onclick="updateRevenuePeriod('month')" id="rev-btn-month" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700">Tháng</button>
                                </div>
                            </div>
                            <div class="relative" style="height: 300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                                <div class="p-2 bg-blue-50 rounded">
                                    <p class="text-xs text-gray-600">Tổng DT</p>
                                    <p class="text-xl font-bold text-blue-600" id="rev-chart-total">0</p>
                                    <p class="text-xs text-gray-400">triệu</p>
                                </div>
                                <div class="p-2 bg-green-50 rounded">
                                    <p class="text-xs text-gray-600">TB/kỳ</p>
                                    <p class="text-xl font-bold text-green-600" id="rev-chart-avg">0</p>
                                    <p class="text-xs text-gray-400">triệu</p>
                                </div>
                                <div class="p-2 bg-purple-50 rounded">
                                    <p class="text-xs text-gray-600">Cao nhất</p>
                                    <p class="text-xl font-bold text-purple-600" id="rev-chart-max">0</p>
                                    <p class="text-xs text-gray-400">triệu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Chào mừng đến với {{ __('ui.title') }}</h2>
                        <p class="text-lg text-gray-600 mb-8">Hệ thống quản lý đại lý AI VN168 - Quản lý ma trận, đơn hàng và hoa hồng</p>
                        <div class="space-x-4">
                            <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.auth.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.auth.register') }}
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
let referralChart = null;
let revenueChart = null;
let currentReferralPeriod = 'day';
let currentRevenuePeriod = 'day';

// Mock data for referral statistics (people count)
const referralMockData = {
    day: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        data: [12, 19, 15, 25, 22, 30, 28],
        label: 'Người giới thiệu theo ngày'
    },
    week: {
        labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
        data: [85, 120, 95, 145],
        label: 'Người giới thiệu theo tuần'
    },
    month: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        data: [280, 350, 420, 480, 530, 600, 680, 650, 720, 780, 850, 920],
        label: 'Người giới thiệu theo tháng'
    }
};

// Mock data for revenue statistics (in million VND)
const revenueMockData = {
    day: {
        labels: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'CN'],
        data: [45, 78, 62, 95, 88, 112, 98],
        label: 'Doanh thu theo ngày'
    },
    week: {
        labels: ['Tuần 1', 'Tuần 2', 'Tuần 3', 'Tuần 4'],
        data: [380, 520, 450, 610],
        label: 'Doanh thu theo tuần'
    },
    month: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        data: [1200, 1450, 1680, 1890, 2100, 2350, 2580, 2450, 2720, 2950, 3150, 3400],
        label: 'Doanh thu theo tháng'
    }
};

document.addEventListener('DOMContentLoaded', function() {
    initReferralChart();
    initRevenueChart();
});

// ========== Referral Chart Functions ==========
function initReferralChart() {
    const ctx = document.getElementById('referralChart');
    if (!ctx) return;

    const data = referralMockData[currentReferralPeriod];
    
    referralChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: data.label,
                data: data.data,
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(79, 70, 229)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' người';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: getReferralStepSize(currentReferralPeriod)
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    updateReferralStats();
}

function getReferralStepSize(period) {
    switch(period) {
        case 'day': return 5;
        case 'week': return 25;
        case 'month': return 100;
        default: return 10;
    }
}

function updateReferralPeriod(period) {
    currentReferralPeriod = period;
    
    // Update button styles
    ['day', 'week', 'month'].forEach(p => {
        const btn = document.getElementById('ref-btn-' + p);
        if (p === period) {
            btn.className = 'px-3 py-1 text-sm rounded bg-indigo-600 text-white';
        } else {
            btn.className = 'px-3 py-1 text-sm rounded bg-gray-200 text-gray-700';
        }
    });

    // Update chart
    const data = referralMockData[period];
    referralChart.data.labels = data.labels;
    referralChart.data.datasets[0].data = data.data;
    referralChart.data.datasets[0].label = data.label;
    referralChart.options.scales.y.ticks.stepSize = getReferralStepSize(period);
    referralChart.update();

    updateReferralStats();
}

function updateReferralStats() {
    const data = referralMockData[currentReferralPeriod].data;
    const total = data.reduce((sum, val) => sum + val, 0);
    const avg = Math.round(total / data.length);
    const max = Math.max(...data);

    document.getElementById('ref-chart-total').textContent = total.toLocaleString('vi-VN');
    document.getElementById('ref-chart-avg').textContent = avg.toLocaleString('vi-VN');
    document.getElementById('ref-chart-max').textContent = max.toLocaleString('vi-VN');
}

// ========== Revenue Chart Functions ==========
function initRevenueChart() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const data = revenueMockData[currentRevenuePeriod];
    
    revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: data.label,
                data: data.data,
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(16, 185, 129)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' triệu';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + ' tr';
                        },
                        stepSize: getRevenueStepSize(currentRevenuePeriod)
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    updateRevenueStats();
}

function getRevenueStepSize(period) {
    switch(period) {
        case 'day': return 20;
        case 'week': return 100;
        case 'month': return 500;
        default: return 50;
    }
}

function updateRevenuePeriod(period) {
    currentRevenuePeriod = period;
    
    // Update button styles
    ['day', 'week', 'month'].forEach(p => {
        const btn = document.getElementById('rev-btn-' + p);
        if (p === period) {
            btn.className = 'px-3 py-1 text-sm rounded bg-green-600 text-white';
        } else {
            btn.className = 'px-3 py-1 text-sm rounded bg-gray-200 text-gray-700';
        }
    });

    // Update chart
    const data = revenueMockData[period];
    revenueChart.data.labels = data.labels;
    revenueChart.data.datasets[0].data = data.data;
    revenueChart.data.datasets[0].label = data.label;
    revenueChart.options.scales.y.ticks.stepSize = getRevenueStepSize(period);
    revenueChart.update();

    updateRevenueStats();
}

function updateRevenueStats() {
    const data = revenueMockData[currentRevenuePeriod].data;
    const total = data.reduce((sum, val) => sum + val, 0);
    const avg = Math.round(total / data.length);
    const max = Math.max(...data);

    document.getElementById('rev-chart-total').textContent = total.toLocaleString('vi-VN');
    document.getElementById('rev-chart-avg').textContent = avg.toLocaleString('vi-VN');
    document.getElementById('rev-chart-max').textContent = max.toLocaleString('vi-VN');
}
</script>
@endauth
@endsection