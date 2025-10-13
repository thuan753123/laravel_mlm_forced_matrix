@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ __('ui.orders_page.heading') }}</h1>
                    <button onclick="openCreateOrderModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('ui.orders_page.create') }}
                    </button>
                </div>
                
                @auth
                    <!-- Order Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Tổng Đơn Hàng</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="total-orders">-</p>
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
                                    <p class="text-sm font-medium text-gray-600">Đã Thanh Toán</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="paid-orders">-</p>
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
                                    <p class="text-sm font-medium text-gray-600">Chờ Thanh Toán</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="pending-orders">-</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-red-50 p-6 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Đã Hủy</p>
                                    <p class="text-2xl font-semibold text-gray-900" id="cancelled-orders">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Table -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Danh Sách Đơn Hàng</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.orders_page.amount') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('ui.orders_page.status') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày Tạo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành Động</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orders-table-body" class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center">
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
                        <p class="text-lg text-gray-600 mb-8">Bạn cần đăng nhập để xem đơn hàng</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('ui.auth.login') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Create Order Modal -->
<div id="create-order-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ __('ui.orders_page.create') }}</h3>
                <button onclick="closeCreateOrderModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="create-order-form" onsubmit="createOrder(event)">
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">{{ __('ui.orders_page.amount') }}</label>
                    <input type="number" id="amount" name="amount" required min="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nhập số tiền (VND)">
                </div>
                <div class="mb-4">
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Tiền tệ</label>
                    <select id="currency" name="currency" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="VND">VND</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateOrderModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        {{ __('ui.common.cancel') }}
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        {{ __('ui.common.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@auth
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadOrdersData();
});

async function loadOrdersData() {
    try {
        // Load order stats
        const statsResponse = await fetch('/api/orders/stats');
        if (statsResponse.ok) {
            const statsData = await statsResponse.json();
            document.getElementById('total-orders').textContent = statsData.stats.total_orders || 0;
            document.getElementById('paid-orders').textContent = statsData.stats.paid_orders || 0;
            document.getElementById('pending-orders').textContent = statsData.stats.pending_orders || 0;
            document.getElementById('cancelled-orders').textContent = statsData.stats.cancelled_orders || 0;
        }

        // Load orders list
        loadOrdersList();
    } catch (error) {
        console.error('Error loading orders data:', error);
    }
}

async function loadOrdersList() {
    try {
        const response = await fetch('/api/orders');
        if (response.ok) {
            const data = await response.json();
            const tbody = document.getElementById('orders-table-body');
            
            if (data.orders && data.orders.length > 0) {
                tbody.innerHTML = data.orders.map(order => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${order.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Intl.NumberFormat('vi-VN').format(order.amount)} ${order.currency}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(order.status)}">
                                ${getStatusText(order.status)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(order.created_at).toLocaleDateString('vi-VN')}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            ${getActionButtons(order)}
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Chưa có đơn hàng nào</td></tr>';
            }
        }
    } catch (error) {
        console.error('Error loading orders list:', error);
        document.getElementById('orders-table-body').innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Lỗi tải danh sách đơn hàng</td></tr>';
    }
}

function getStatusClass(status) {
    switch (status) {
        case 'paid':
            return 'bg-green-100 text-green-800';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'paid':
            return 'Đã thanh toán';
        case 'pending':
            return 'Chờ thanh toán';
        case 'cancelled':
            return 'Đã hủy';
        default:
            return status;
    }
}

function getActionButtons(order) {
    let buttons = '';
    
    if (order.status === 'pending') {
        buttons += `<button onclick="payOrder(${order.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">${__('ui.orders_page.pay')}</button>`;
        buttons += `<button onclick="cancelOrder(${order.id})" class="text-red-600 hover:text-red-900">${__('ui.orders_page.cancel')}</button>`;
    }
    
    buttons += `<button onclick="viewOrder(${order.id})" class="text-gray-600 hover:text-gray-900 ml-3">${__('ui.common.view')}</button>`;
    
    return buttons;
}

function openCreateOrderModal() {
    document.getElementById('create-order-modal').classList.remove('hidden');
}

function closeCreateOrderModal() {
    document.getElementById('create-order-modal').classList.add('hidden');
    document.getElementById('create-order-form').reset();
}

async function createOrder(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = {
        amount: parseInt(formData.get('amount')),
        currency: formData.get('currency')
    };
    
    try {
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            const result = await response.json();
            alert('Đơn hàng đã được tạo thành công!');
            closeCreateOrderModal();
            loadOrdersData();
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || 'Tạo đơn hàng thất bại'));
        }
    } catch (error) {
        console.error('Error creating order:', error);
        alert('Lỗi: Tạo đơn hàng thất bại');
    }
}

async function payOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn thanh toán đơn hàng này?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/orders/${orderId}/pay`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            alert('Đơn hàng đã được thanh toán thành công!');
            loadOrdersData();
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || 'Thanh toán thất bại'));
        }
    } catch (error) {
        console.error('Error paying order:', error);
        alert('Lỗi: Thanh toán thất bại');
    }
}

async function cancelOrder(orderId) {
    if (!confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            alert('Đơn hàng đã được hủy!');
            loadOrdersData();
        } else {
            const error = await response.json();
            alert('Lỗi: ' + (error.message || 'Hủy đơn hàng thất bại'));
        }
    } catch (error) {
        console.error('Error cancelling order:', error);
        alert('Lỗi: Hủy đơn hàng thất bại');
    }
}

function viewOrder(orderId) {
    window.open(`/orders/${orderId}`, '_blank');
}
</script>
@endauth
@endsection