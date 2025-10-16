@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">{{ __('ui.comm_page.heading') }}</h1>
                
                @auth
                    <!-- Commission Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Hoa Hồng</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-commissions">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Đã Duyệt</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="approved-commissions">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Chờ Duyệt</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="pending-commissions">-</p>
                                </div>
                            </div>
                        </div>

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

                    <!-- Commission Summary -->
                    <div class="bg-white p-6 rounded-lg shadow mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('ui.comm_page.summary') }}</h3>
                        <div id="commission-summary" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600" id="summary-total">-</div>
                                <div class="text-sm text-gray-500">Tổng Hoa Hồng</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600" id="summary-approved">-</div>
                                <div class="text-sm text-gray-500">Đã Duyệt</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600" id="summary-pending">-</div>
                                <div class="text-sm text-gray-500">Chờ Duyệt</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600" id="summary-void">-</div>
                                <div class="text-sm text-gray-500">Hủy Bỏ</div>
                            </div>
                        </div>
                    </div>

                    <!-- Commission History -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">{{ __('ui.comm_page.history') }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.comm_page.level') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.comm_page.amount') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.comm_page.status') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người Trả</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                                        </tr>
                                    </thead>
                                    <tbody id="commissions-table-body" class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="6" class="px-6 py-4 text-center">
                                                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600 mx-auto"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Vui lòng đăng nhập</h2>
                        <p class="text-lg text-gray-600 mb-8">Bạn cần đăng nhập để xem hoa hồng</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('ui.auth.login') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCommissionsData();
});

async function loadCommissionsData() {
    try {
        // Load commission stats
        const statsResponse = await fetch('/api/commissions/stats');
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            document.getElementById('total-commissions').textContent = new Intl.NumberFormat('vi-VN').format(statsData.stats.total_amount || 0);
            document.getElementById('approved-commissions').textContent = new Intl.NumberFormat('vi-VN').format(statsData.stats.approved_amount || 0);
            document.getElementById('pending-commissions').textContent = new Intl.NumberFormat('vi-VN').format(statsData.stats.pending_amount || 0);
        }

        // Load commission summary
        loadCommissionSummary();
        
        // Load commission history
        loadCommissionHistory();
        
        // Load current cycle
        loadCurrentCycle();
    } catch (error) {
        console.error('Error loading commissions data:', error);
    }
}

async function loadCommissionSummary() {
    try {
        const response = await fetch('/api/commissions/summary');
        if (response.ok) {
            const data = await response.json();
            document.getElementById('summary-total').textContent = new Intl.NumberFormat('vi-VN').format(data.summary.total_amount || 0);
            document.getElementById('summary-approved').textContent = new Intl.NumberFormat('vi-VN').format(data.summary.approved_amount || 0);
            document.getElementById('summary-pending').textContent = new Intl.NumberFormat('vi-VN').format(data.summary.pending_amount || 0);
            document.getElementById('summary-void').textContent = new Intl.NumberFormat('vi-VN').format(data.summary.void_amount || 0);
        }
    } catch (error) {
        console.error('Error loading commission summary:', error);
    }
}

async function loadCommissionHistory() {
    try {
        const response = await fetch('/api/commissions/me?per_page=20');
        if (response.ok) {
            const data = await response.json();
            const tbody = document.getElementById('commissions-table-body');
            
            if (data.commissions && data.commissions.length > 0) {
                tbody.innerHTML = data.commissions.map(commission => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${commission.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Tầng ${commission.level}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Intl.NumberFormat('vi-VN').format(commission.amount)} ${commission.currency}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(commission.status)}">
                                ${getStatusText(commission.status)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${commission.payer_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(commission.created_at).toLocaleDateString('vi-VN')}</td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Chưa có hoa hồng nào</td></tr>';
            }
        }
    } catch (error) {
        console.error('Error loading commission history:', error);
        document.getElementById('commissions-table-body').innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Lỗi tải lịch sử hoa hồng</td></tr>';
    }
}

async function loadCurrentCycle() {
    try {
        const response = await fetch('/api/cycles/current');
        if (response.ok) {
            const data = await response.json();
            if (data.cycle) {
                document.getElementById('current-cycle').textContent = data.cycle.period;
            }
        }
    } catch (error) {
        console.error('Error loading current cycle:', error);
    }
}

function getStatusClass(status) {
    switch (status) {
        case 'approved':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'void':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'approved':
            return 'Đã duyệt';
        case 'pending':
            return 'Chờ duyệt';
        case 'void':
            return 'Hủy bỏ';
        default:
            return status;
    }
}
</script>
@endauth
@endsection