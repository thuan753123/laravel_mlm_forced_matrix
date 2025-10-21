@extends('layouts.app')

@section('page-title', 'Chiết Khấu Của Tôi')
@section('page-subtitle', 'Thông tin chiết khấu hiện tại')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Chiết Khấu Của Tôi</h1>
            <p class="mt-2 text-sm text-gray-600">Xem thông tin chiết khấu và doanh thu hiện tại của bạn</p>
        </div>

        <!-- Current Revenue & Discount Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Doanh thu hiện tại -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium opacity-90">Doanh thu hiện tại</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ formatMoney($mockRevenue) }}</p>
                <p class="text-sm opacity-75 mt-1">Tổng doanh thu giới thiệu</p>
            </div>

            <!-- Bậc hiện tại -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium opacity-90">Bậc hiện tại</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">Bậc {{ $currentTier['tier'] ?? 'N/A' }}</p>
                <p class="text-sm opacity-75 mt-1">{{ $currentTier['tier_name'] ?? 'Chưa xác định' }}</p>
            </div>

            <!-- Tỷ lệ chiết khấu -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium opacity-90">Chiết khấu ước tính</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <p class="text-3xl font-bold">{{ formatMoney($calculation['total_discount']) }}</p>
                <p class="text-sm opacity-75 mt-1">{{ number_format($calculation['discount_percentage'], 2) }}% trên doanh thu</p>
            </div>
        </div>

        <!-- Progress to Next Tier -->
        @if($nextTarget)
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Tiến độ lên bậc tiếp theo</h3>
                    <p class="text-sm text-gray-600">Còn <span class="font-bold text-indigo-600">{{ formatMoney($nextTarget['revenue_needed']) }}</span> để đạt bậc {{ $nextTarget['next_tier']['tier'] }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($nextTarget['progress'], 1) }}%</p>
                    <p class="text-xs text-gray-500">Hoàn thành</p>
                </div>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-4 rounded-full transition-all duration-500 ease-out" 
                     style="width: {{ min($nextTarget['progress'], 100) }}%"></div>
            </div>
            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-gray-600">Bậc tiếp theo</p>
                    <p class="font-semibold text-gray-900">Bậc {{ $nextTarget['next_tier']['tier'] }} - {{ $nextTarget['next_tier']['tier_name'] }}</p>
                </div>
                <div class="bg-gray-50 rounded p-3">
                    <p class="text-gray-600">Tỷ lệ chiết khấu mới</p>
                    <p class="font-semibold text-green-600">{{ $nextTarget['next_tier']['discount_rate'] }}%</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Chi tiết chiết khấu theo bậc -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Chi Tiết Chiết Khấu</h3>
            
            @if(count($calculation['breakdown']) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bậc</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doanh thu áp dụng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tỷ lệ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chiết khấu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($calculation['breakdown'] as $item)
                        <tr class="{{ $loop->last ? 'bg-blue-50 font-semibold' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Bậc {{ $item['tier'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ formatMoney($item['applicable_revenue']) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item['discount_rate'] }}%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                {{ formatMoney($item['discount_amount']) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-indigo-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900">Tổng chiết khấu</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                {{ formatMoney($calculation['total_discount']) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-4">Chưa có chiết khấu</p>
            @endif
        </div>

        <!-- Bảng tất cả các bậc chiết khấu -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Tất Cả Các Bậc Chiết Khấu</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bậc</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên bậc</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mức doanh thu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DT áp dụng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tỷ lệ CK</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allTiers as $tier)
                        <tr class="{{ isset($currentTier['tier']) && $currentTier['tier'] == $tier->tier ? 'bg-indigo-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ isset($currentTier['tier']) && $currentTier['tier'] == $tier->tier ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-800' }}">
                                    Bậc {{ $tier->tier }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $tier->tier_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($tier->revenue_to)
                                    {{ formatMoney($tier->revenue_from) }} - {{ formatMoney($tier->revenue_to) }}
                                @else
                                    Từ {{ formatMoney($tier->revenue_from) }} trở lên
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($tier->applicable_revenue_to)
                                    {{ formatMoney($tier->applicable_revenue_from) }} - {{ formatMoney($tier->applicable_revenue_to) }}
                                @else
                                    Từ {{ formatMoney($tier->applicable_revenue_from) }} trở lên
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $tier->discount_rate }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $tier->note ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-8 flex gap-4">
            <a href="{{ route('discounts.calculator') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Tính toán chiết khấu
            </a>
            <a href="{{ route('discounts.index') }}" class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Xem chính sách chiết khấu
            </a>
        </div>
    </div>
</div>
@endsection

