<?php $__env->startSection('content'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6"><?php echo e(__('ui.nav.dashboard')); ?></h1>
                
                <?php if(auth()->guard()->check()): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Matrix Stats -->
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Downline</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-downline">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Orders Stats -->
                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Đơn Hàng</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-orders">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Commissions Stats -->
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Hoa Hồng</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-commissions">-</p>
                                </div>
                            </div>
                        </div>

                        <!-- Current Cycle -->
                        <div class="bg-purple-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-purple-100 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Chu Kỳ Hiện Tại</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="current-cycle">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4"><?php echo e(__('ui.orders_page.heading')); ?></h3>
                            <p class="text-gray-600 mb-4">Quản lý đơn hàng và thanh toán</p>
                            <a href="<?php echo e(route('orders.index')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('ui.orders_page.create')); ?>

                            </a>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4"><?php echo e(__('ui.matrix_page.heading')); ?></h3>
                            <p class="text-gray-600 mb-4">Xem cây ma trận và thống kê</p>
                            <a href="<?php echo e(route('matrix.index')); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('ui.matrix_page.visualization')); ?>

                            </a>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4"><?php echo e(__('ui.comm_page.heading')); ?></h3>
                            <p class="text-gray-600 mb-4">Xem hoa hồng và lịch sử</p>
                            <a href="<?php echo e(route('commissions.index')); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('ui.comm_page.summary')); ?>

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
                <?php else: ?>
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Chào mừng đến với <?php echo e(__('ui.title')); ?></h2>
                        <p class="text-lg text-gray-600 mb-8">Hệ thống quản lý ma trận MLM hiện đại</p>
                        <div class="space-x-4">
                            <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('ui.auth.login')); ?>

                            </a>
                            <a href="<?php echo e(route('register')); ?>" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <?php echo e(__('ui.auth.register')); ?>

                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if(auth()->guard()->check()): ?>
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
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/dashboard.blade.php ENDPATH**/ ?>