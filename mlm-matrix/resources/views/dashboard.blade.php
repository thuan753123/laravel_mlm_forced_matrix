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
                            <p class="text-gray-600 mb-4">Xem hoa hồng và lịch sử</p>
                            <a href="{{ route('commissions.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('ui.comm_page.summary') }}
                            </a>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Hoạt Động Gần Đây</h3>
                        <div id="recent-activity" class="space-y-4">
                            <div class="flex items-center justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Chào mừng đến với {{ __('ui.title') }}</h2>
                        <p class="text-lg text-gray-600 mb-8">Hệ thống quản lý ma trận MLM hiện đại</p>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard data
    loadDashboardData();
});

async function loadDashboardData() {
    try {
        // Load matrix stats
        const matrixResponse = await fetch('/api/matrix/stats');
        if (matrixResponse.ok) {
            const matrixData = await matrixResponse.json();
            document.getElementById('total-downline').textContent = matrixData.user_stats.total_downline || 0;
        }

        // Load order stats
        const orderResponse = await fetch('/api/orders/stats');
        if (orderResponse.ok) {
            const orderData = await orderResponse.json();
            document.getElementById('total-orders').textContent = orderData.stats.total_orders || 0;
        }

        // Load commission stats
        const commissionResponse = await fetch('/api/commissions/stats');
        if (commissionResponse.ok) {
            const commissionData = await commissionResponse.json();
            document.getElementById('total-commissions').textContent = new Intl.NumberFormat('vi-VN').format(commissionData.stats.total_amount || 0);
        }

        // Load current cycle
        const cycleResponse = await fetch('/api/cycles/current');
        if (cycleResponse.ok) {
            const cycleData = await cycleResponse.json();
            document.getElementById('current-cycle').textContent = cycleData.cycle ? cycleData.cycle.period : 'N/A';
        }

        // Load recent activity
        loadRecentActivity();
    } catch (error) {
        console.error('Error loading dashboard data:', error);
    }
}

async function loadRecentActivity() {
    try {
        const response = await fetch('/api/commissions/history?limit=5');
        if (response.ok) {
            const data = await response.json();
            const activityContainer = document.getElementById('recent-activity');
            
            if (data.history && data.history.length > 0) {
                activityContainer.innerHTML = data.history.map(commission => `
                    <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Hoa hồng tầng ${commission.level}</p>
                                <p class="text-xs text-gray-500">${new Date(commission.created_at).toLocaleDateString('vi-VN')}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-green-600">+${new Intl.NumberFormat('vi-VN').format(commission.amount)} VND</p>
                            <p class="text-xs text-gray-500">${commission.status}</p>
                        </div>
                    </div>
                `).join('');
            } else {
                activityContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Chưa có hoạt động nào</p>';
            }
        }
    } catch (error) {
        console.error('Error loading recent activity:', error);
        document.getElementById('recent-activity').innerHTML = '<p class="text-red-500 text-center py-4">Lỗi tải dữ liệu</p>';
    }
}
</script>
@endauth
@endsection