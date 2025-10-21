@extends('layouts.app')

@section('title', 'Thêm Bậc Chiết khấu')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6 lg:p-8">
    <!-- Header -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-plus-circle text-green-600 mr-3"></i>Thêm Bậc Chiết khấu
                    </h1>
                    <p class="text-gray-600">Tạo mới cấu hình chiết khấu</p>
                </div>
                <a href="{{ route('admin.discounts.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.discounts.store') }}" method="POST" class="bg-white rounded-2xl shadow-lg overflow-hidden">
            @csrf
            
            <div class="p-6 md:p-8 space-y-6">
                <!-- Tier -->
                <div>
                    <label for="tier" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-layer-group text-blue-500 mr-2"></i>Bậc chiết khấu <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="tier" 
                           name="tier" 
                           value="{{ old('tier') }}"
                           min="1"
                           class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 @error('tier') border-red-500 @enderror"
                           required>
                    @error('tier')
                        <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tier Name -->
                <div>
                    <label for="tier_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag text-blue-500 mr-2"></i>Tên bậc <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="tier_name" 
                           name="tier_name" 
                           value="{{ old('tier_name') }}"
                           placeholder="VD: NPP mới, NPP tiêu chuẩn..."
                           class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200 @error('tier_name') border-red-500 @enderror"
                           required>
                    @error('tier_name')
                        <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Revenue Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="revenue_from" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-arrow-right text-green-500 mr-2"></i>Doanh thu từ (triệu) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="revenue_from" 
                               name="revenue_from" 
                               value="{{ old('revenue_from') }}"
                               min="0"
                               step="0.01"
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 @error('revenue_from') border-red-500 @enderror"
                               required>
                        @error('revenue_from')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="revenue_to" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-arrow-left text-green-500 mr-2"></i>Doanh thu đến (triệu)
                        </label>
                        <input type="number" 
                               id="revenue_to" 
                               name="revenue_to" 
                               value="{{ old('revenue_to') }}"
                               min="0"
                               step="0.01"
                               placeholder="Để trống nếu không giới hạn"
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200 @error('revenue_to') border-red-500 @enderror">
                        @error('revenue_to')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-600"><i class="fas fa-info-circle mr-1"></i>Để trống nếu bậc cao nhất</p>
                    </div>
                </div>

                <!-- Applicable Revenue Range -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="applicable_revenue_from" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calculator text-purple-500 mr-2"></i>DT áp dụng từ (triệu) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="applicable_revenue_from" 
                               name="applicable_revenue_from" 
                               value="{{ old('applicable_revenue_from') }}"
                               min="0"
                               step="0.01"
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all duration-200 @error('applicable_revenue_from') border-red-500 @enderror"
                               required>
                        @error('applicable_revenue_from')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="applicable_revenue_to" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calculator text-purple-500 mr-2"></i>DT áp dụng đến (triệu)
                        </label>
                        <input type="number" 
                               id="applicable_revenue_to" 
                               name="applicable_revenue_to" 
                               value="{{ old('applicable_revenue_to') }}"
                               min="0"
                               step="0.01"
                               placeholder="Để trống nếu không giới hạn"
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all duration-200 @error('applicable_revenue_to') border-red-500 @enderror">
                        @error('applicable_revenue_to')
                            <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Discount Rate -->
                <div>
                    <label for="discount_rate" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-percentage text-yellow-500 mr-2"></i>Tỷ lệ chiết khấu (%) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="discount_rate" 
                           name="discount_rate" 
                           value="{{ old('discount_rate') }}"
                           min="0"
                           max="100"
                           step="0.01"
                           class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-yellow-100 focus:border-yellow-500 transition-all duration-200 @error('discount_rate') border-red-500 @enderror"
                           required>
                    @error('discount_rate')
                        <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Note -->
                <div>
                    <label for="note" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-gray-500 mr-2"></i>Ghi chú
                    </label>
                    <textarea id="note" 
                              name="note" 
                              rows="3"
                              placeholder="Ghi chú về bậc chiết khấu này..."
                              class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-gray-100 focus:border-gray-500 transition-all duration-200 @error('note') border-red-500 @enderror">{{ old('note') }}</textarea>
                    @error('note')
                        <p class="mt-2 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div>
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-4 cursor-pointer">
                        <span class="ml-3 text-sm font-semibold text-gray-700">
                            <i class="fas fa-toggle-on text-blue-500 mr-2"></i>Kích hoạt ngay
                        </span>
                    </label>
                    <p class="mt-2 ml-8 text-sm text-gray-600"><i class="fas fa-info-circle mr-1"></i>Bậc sẽ có hiệu lực ngay sau khi tạo</p>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.discounts.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Hủy
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-save mr-2"></i>
                    Lưu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

