@extends('layouts.app')

@section('title', 'Quản lý Chiết khấu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6 lg:p-8">
    <!-- Success Message -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-percentage text-blue-600 mr-3"></i>Quản lý Chiết khấu
                    </h1>
                    <p class="text-gray-600">Quản lý cấu hình chiết khấu cho các cấp độ đại lý</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.discounts.create') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Thêm bậc mới
                    </a>
                    <a href="{{ route('admin.discounts.calculator') }}" 
                       class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-calculator mr-2"></i>
                        Máy tính Chiết khấu
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tier Cards Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $tierColors = [
                    1 => ['from' => 'from-green-400', 'to' => 'to-green-600', 'icon' => 'fa-seedling'],
                    2 => ['from' => 'from-blue-400', 'to' => 'to-blue-600', 'icon' => 'fa-star'],
                    3 => ['from' => 'from-purple-400', 'to' => 'to-purple-600', 'icon' => 'fa-gem'],
                    4 => ['from' => 'from-yellow-400', 'to' => 'to-yellow-600', 'icon' => 'fa-crown'],
                ];
                $uniqueTiers = $tiers->unique('tier');
            @endphp

            @foreach($uniqueTiers as $tier)
                @php
                    $color = $tierColors[$tier->tier] ?? ['from' => 'from-gray-400', 'to' => 'to-gray-600', 'icon' => 'fa-star'];
                    $tierGroup = $tiers->where('tier', $tier->tier);
                    $maxRate = $tierGroup->max('discount_rate');
                @endphp
                <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                    <div class="bg-gradient-to-br {{ $color['from'] }} {{ $color['to'] }} p-6 text-white">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Bậc {{ $tier->tier }}</span>
                            <i class="fas {{ $color['icon'] }} text-2xl opacity-80"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-1">{{ $tier->tier_name }}</h3>
                        <p class="text-white/90 text-sm">
                            @if($tier->revenue_to)
                                {{ number_format($tier->revenue_from) }}-{{ number_format($tier->revenue_to) }} triệu
                            @else
                                ≥ {{ number_format($tier->revenue_from) }} triệu
                            @endif
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-600 text-sm">Chiết khấu tối đa</span>
                            <span class="text-3xl font-bold text-gray-900">{{ $maxRate }}%</span>
                        </div>
                        @if($tierGroup->count() > 1)
                            <div class="text-xs text-gray-500 bg-gray-50 rounded-lg p-3">
                                <i class="fas fa-info-circle mr-1"></i>Có {{ $tierGroup->count() }} mức chiết khấu
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Detailed Table Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-table text-gray-600 mr-2"></i>Bảng Chi tiết Chiết khấu
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Bậc
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Phân loại
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Mức doanh thu
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Doanh thu áp dụng
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Tỷ lệ
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Ghi chú
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $currentTier = null;
                            $rowColors = [
                                1 => 'bg-green-50',
                                2 => 'bg-blue-50',
                                3 => 'bg-purple-50',
                                4 => 'bg-yellow-50',
                            ];
                        @endphp
                        @foreach($tiers as $tier)
                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $rowColors[$tier->tier] ?? 'bg-gray-50' }}">
                                @if($currentTier !== $tier->tier)
                                    <td rowspan="{{ $tiers->where('tier', $tier->tier)->count() }}" class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br {{ $tierColors[$tier->tier]['from'] ?? 'from-gray-400' }} {{ $tierColors[$tier->tier]['to'] ?? 'to-gray-600' }} text-white font-bold text-lg shadow-md">
                                            {{ $tier->tier }}
                                        </div>
                                    </td>
                                    @php
                                        $currentTier = $tier->tier;
                                    @endphp
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <i class="fas {{ $tierColors[$tier->tier]['icon'] ?? 'fa-star' }} text-gray-400 mr-2"></i>
                                        <span class="font-semibold text-gray-900">{{ $tier->tier_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">
                                        @if($tier->revenue_to)
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-arrow-right text-gray-400 text-xs mr-2"></i>
                                                {{ number_format($tier->revenue_from, 0) }} - {{ number_format($tier->revenue_to, 0) }} triệu
                                            </span>
                                        @else
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-infinity text-gray-400 text-xs mr-2"></i>
                                                ≥ {{ number_format($tier->revenue_from, 0) }} triệu
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700">
                                        @if($tier->applicable_revenue_to)
                                            {{ number_format($tier->applicable_revenue_from, 0) }} - {{ number_format($tier->applicable_revenue_to, 0) }} triệu
                                        @else
                                            ≥ {{ number_format($tier->applicable_revenue_from, 0) }} triệu
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-md">
                                        {{ $tier->discount_rate }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600">{{ $tier->note }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($tier->is_active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause-circle mr-1"></i>Tạm dừng
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('admin.discounts.edit', $tier->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                           title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.discounts.destroy', $tier->id) }}" 
                                              method="POST" 
                                              class="inline-block"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa bậc chiết khấu này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors duration-200"
                                                    title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Formula Section -->
    <div class="max-w-7xl mx-auto">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 md:p-8 border-2 border-blue-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Công thức tính Chiết khấu</h3>
                    <div class="bg-white/80 backdrop-blur rounded-xl p-4 border border-blue-200">
                        <p class="text-lg font-mono font-semibold text-gray-800 text-center">
                            Chiết khấu tháng = <span class="text-blue-600">Σ</span>(Doanh thu từng bậc × Tỷ lệ chiết khấu tương ứng)
                        </p>
                    </div>
                    <div class="mt-4 text-sm text-gray-700">
                        <p class="mb-2"><i class="fas fa-lightbulb text-yellow-500 mr-2"></i><strong>Lưu ý:</strong> Chiết khấu được tính theo phương pháp lũy tiến</p>
                        <p><i class="fas fa-chart-line text-green-500 mr-2"></i>Ví dụ: Với doanh thu 1,500 triệu, bạn sẽ nhận chiết khấu từ cả 4 bậc</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

