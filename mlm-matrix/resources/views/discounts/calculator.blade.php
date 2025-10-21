@extends('layouts.app')

@section('title', 'Máy tính Chiết khấu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-calculator text-blue-600 mr-3"></i>Máy tính Chiết khấu
                    </h1>
                    <p class="text-gray-600">Tính toán chiết khấu dựa trên doanh thu</p>
                </div>
                <a href="{{ route('discounts.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Calculator Section -->
        <div class="space-y-6">
            <!-- Input Form -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>Nhập Doanh thu
                    </h2>
                </div>
                <div class="p-6">
                    <form id="discountCalculatorForm">
                        <div class="mb-6">
                            <label for="revenue" class="block text-sm font-semibold text-gray-700 mb-2">
                                Doanh thu (Triệu đồng) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-money-bill-wave text-gray-400"></i>
                                </div>
                                <input type="number" 
                                       id="revenue" 
                                       name="revenue" 
                                       class="block w-full pl-12 pr-4 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Nhập doanh thu..." 
                                       step="0.01" 
                                       min="0"
                                       required>
                            </div>
                        </div>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-calculator mr-2"></i>
                            Tính chiết khấu
                        </button>
                    </form>
                </div>
            </div>

            <!-- Result Section -->
            <div id="calculationResult" class="hidden">
                <!-- Breakdown Table -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200">
                        <h2 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-chart-pie text-green-600 mr-2"></i>Kết quả tính toán
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Bậc</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">DT áp dụng</th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Tỷ lệ</th>
                                        <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">Chiết khấu</th>
                                    </tr>
                                </thead>
                                <tbody id="breakdownTable" class="divide-y divide-gray-200">
                                    <!-- Được điền bằng JavaScript -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gradient-to-r from-green-50 to-emerald-50 border-t-2 border-green-200">
                                        <th colspan="3" class="px-4 py-4 text-right text-sm font-bold text-gray-900">
                                            <i class="fas fa-coins mr-2"></i>TỔNG CHIẾT KHẤU:
                                        </th>
                                        <th id="totalDiscount" class="px-4 py-4 text-right text-xl font-bold text-green-600">
                                            0 triệu
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <!-- Tier Info -->
                        <div id="tierInfo" class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                            <!-- Được điền bằng JavaScript -->
                        </div>

                        <!-- Next Target -->
                        <div id="nextTarget" class="hidden mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                            <!-- Được điền bằng JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reference Section -->
        <div class="space-y-6">
            <!-- Examples -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-lightbulb text-purple-600 mr-2"></i>Ví dụ tính toán
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Doanh thu</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">Chiết khấu</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-700 uppercase">% trên DT</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($examples as $example)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            {{ formatMoney($example['total_revenue']) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-semibold text-right text-green-600">
                                            {{ formatMoney($example['total_discount']) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-right text-gray-700">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">
                                                {{ $example['total_revenue'] > 0 ? number_format(($example['total_discount'] / $example['total_revenue']) * 100, 2) : 0 }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tier Reference -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-orange-50 to-red-50 border-b border-orange-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-list-alt text-orange-600 mr-2"></i>Bảng chiết khấu
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $tierColors = [
                            1 => 'from-green-400 to-green-600',
                            2 => 'from-blue-400 to-blue-600',
                            3 => 'from-purple-400 to-purple-600',
                            4 => 'from-yellow-400 to-yellow-600',
                        ];
                        $uniqueTiers = $tiers->unique('tier');
                    @endphp
                    
                    <div class="space-y-3">
                        @foreach($uniqueTiers as $tier)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r {{ $tierColors[$tier->tier] ?? 'from-gray-400 to-gray-600' }} text-white shadow-md hover:shadow-lg transition-all duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-white/20 backdrop-blur font-bold text-lg">
                                        {{ $tier->tier }}
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $tier->tier_name }}</div>
                                        <div class="text-sm opacity-90">
                                            @if($tier->revenue_to)
                                                {{ number_format($tier->revenue_from, 0) }}-{{ number_format($tier->revenue_to, 0) }} triệu
                                            @else
                                                ≥{{ number_format($tier->revenue_from, 0) }} triệu
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold">
                                        {{ $tiers->where('tier', $tier->tier)->max('discount_rate') }}%
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('discountCalculatorForm');
    const resultDiv = document.getElementById('calculationResult');
    
    // Helper function to format money
    function formatMoney(amount) {
        if (amount >= 1000) {
            const billions = amount / 1000;
            if (billions == Math.floor(billions)) {
                return billions.toLocaleString() + ' tỷ';
            }
            return billions.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' tỷ';
        }
        return amount.toLocaleString() + ' triệu';
    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const revenue = document.getElementById('revenue').value;
        
        if (!revenue || revenue <= 0) {
            alert('Vui lòng nhập doanh thu hợp lệ!');
            return;
        }
        
        try {
            const response = await fetch('{{ route("discounts.calculate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ revenue: parseFloat(revenue) })
            });
            
            const result = await response.json();
            
            if (result.success) {
                displayResult(result.data);
            } else {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    });
    
    function displayResult(data) {
        const { calculation, current_tier, next_tier_target } = data;
        
        const tierBadgeColors = {
            1: 'bg-green-100 text-green-800',
            2: 'bg-blue-100 text-blue-800',
            3: 'bg-purple-100 text-purple-800',
            4: 'bg-yellow-100 text-yellow-800',
        };
        
        const breakdownTable = document.getElementById('breakdownTable');
        breakdownTable.innerHTML = '';
        
        calculation.breakdown.forEach(item => {
            const badgeClass = tierBadgeColors[item.tier] || 'bg-gray-100 text-gray-800';
            const row = `
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 text-sm">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold ${badgeClass}">
                            Bậc ${item.tier}
                        </span>
                        <span class="ml-2 text-gray-700">${item.tier_name}</span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right font-medium text-gray-900">
                        ${formatMoney(item.applicable_revenue)}
                    </td>
                    <td class="px-4 py-3 text-sm text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600">
                            ${item.discount_rate}%
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-right font-bold text-green-600">
                        ${formatMoney(item.discount_amount)}
                    </td>
                </tr>
            `;
            breakdownTable.innerHTML += row;
        });
        
        document.getElementById('totalDiscount').textContent = formatMoney(calculation.total_discount);
        
        const tierInfo = document.getElementById('tierInfo');
        if (current_tier) {
            const percentage = calculation.total_revenue > 0 
                ? ((calculation.total_discount / calculation.total_revenue) * 100).toFixed(2)
                : 0;
            
            tierInfo.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-900 mb-1">
                            <span class="text-blue-600">Bậc hiện tại:</span> Bậc ${current_tier.tier} - ${current_tier.tier_name}
                        </p>
                        <p class="text-sm text-gray-700">
                            Tỷ lệ chiết khấu tổng: <strong class="text-blue-600">${percentage}%</strong> trên tổng doanh thu
                        </p>
                    </div>
                </div>
            `;
        } else {
            tierInfo.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-blue-500 mr-3"></i>
                    <span class="font-semibold text-gray-900">Chưa đạt bậc nào</span>
                </div>
            `;
        }
        
        const nextTarget = document.getElementById('nextTarget');
        if (next_tier_target) {
            nextTarget.classList.remove('hidden');
            nextTarget.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-flag-checkered text-yellow-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-semibold text-gray-900 mb-1">
                            <span class="text-yellow-600">Bậc tiếp theo:</span> Bậc ${next_tier_target.next_tier} - ${next_tier_target.next_tier_name}
                        </p>
                        <p class="text-sm text-gray-700">
                            Cần thêm <strong class="text-yellow-600">${formatMoney(next_tier_target.revenue_needed)}</strong> 
                            để đạt ${formatMoney(next_tier_target.target_revenue)}
                        </p>
                    </div>
                </div>
            `;
        } else {
            nextTarget.classList.add('hidden');
        }
        
        resultDiv.classList.remove('hidden');
        resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>
@endsection

