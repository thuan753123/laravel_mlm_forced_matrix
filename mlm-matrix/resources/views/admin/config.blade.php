@extends('layouts.app')

@section('title', 'Cấu hình MLM')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-4 md:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="max-w-7xl mx-auto mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-cog text-blue-600 mr-3"></i>Cấu hình Hệ thống MLM
                    </h1>
                    <p class="text-gray-600">Quản lý các thông số và quy tắc của hệ thống đa cấp</p>
                </div>
                <button type="button" 
                        onclick="resetConfig()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                    <i class="fas fa-undo mr-2"></i>
                    Đặt lại mặc định
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
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

    @if(session('error'))
    <div class="max-w-7xl mx-auto mb-6">
        <div class="bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 p-4 rounded-lg shadow">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl mr-3"></i>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form -->
    <form id="configForm" class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Matrix Configuration -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-project-diagram text-blue-600 mr-2"></i>Cấu hình Ma trận
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Width -->
                    <div>
                        <label for="width" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-arrows-alt-h text-blue-500 mr-2"></i>Độ rộng ma trận
                        </label>
                        <input type="number" 
                               id="width" 
                               name="width" 
                               min="1" 
                               max="10" 
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                               required>
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>Số vị trí trực tiếp tối đa (1-10)
                        </p>
                    </div>

                    <!-- Max Depth -->
                    <div>
                        <label for="max_depth" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-layer-group text-blue-500 mr-2"></i>Độ sâu tối đa
                        </label>
                        <input type="number" 
                               id="max_depth" 
                               name="max_depth" 
                               min="1" 
                               max="20" 
                               class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                               required>
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>Số tầng tối đa trong ma trận (1-20)
                        </p>
                    </div>

                    <!-- Placement Mode -->
                    <div>
                        <label for="placement_mode" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-blue-500 mr-2"></i>Chế độ đặt vị trí
                        </label>
                        <select id="placement_mode" 
                                name="placement_mode" 
                                class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                required>
                            <option value="forced">Ép buộc</option>
                            <option value="auto">Tự động</option>
                        </select>
                    </div>

                    <!-- Spillover Mode -->
                    <div>
                        <label for="spillover_mode" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sitemap text-blue-500 mr-2"></i>Chế độ spillover
                        </label>
                        <select id="spillover_mode" 
                                name="spillover_mode" 
                                class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all duration-200"
                                required>
                            <option value="bfs">Breadth First (BFS)</option>
                            <option value="balanced">Cân bằng</option>
                            <option value="leftmost">Trái nhất</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Commission Configuration -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-green-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-coins text-green-600 mr-2"></i>Cấu hình Hoa hồng
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Capping Per Cycle -->
                    <div>
                        <label for="capping_per_cycle" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>Giới hạn hoa hồng mỗi chu kỳ
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 font-semibold">$</span>
                            </div>
                            <input type="number" 
                                   id="capping_per_cycle" 
                                   name="capping_per_cycle" 
                                   min="0" 
                                   step="0.01"
                                   class="block w-full pl-10 pr-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200"
                                   required>
                        </div>
                        <p class="mt-2 text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>Giới hạn hoa hồng tối đa mỗi chu kỳ
                        </p>
                    </div>

                    <!-- Cycle Period -->
                    <div>
                        <label for="cycle_period" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-green-500 mr-2"></i>Chu kỳ hoa hồng
                        </label>
                        <select id="cycle_period" 
                                name="cycle_period" 
                                class="block w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200"
                                required>
                            <option value="daily">Hàng ngày</option>
                            <option value="weekly">Hàng tuần</option>
                            <option value="monthly">Hàng tháng</option>
                        </select>
                    </div>

                    <!-- Commission Levels -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-percentage text-green-500 mr-2"></i>Hoa hồng theo tầng (%)
                        </label>
                        <div id="commissionLevels" class="space-y-3">
                            <!-- Will be populated dynamically -->
                        </div>
                        <button type="button" 
                                onclick="addCommissionLevel()"
                                class="mt-4 w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Thêm tầng hoa hồng
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Qualification Rules -->
        <div class="max-w-7xl mx-auto mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-200">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-clipboard-check text-purple-600 mr-2"></i>Quy tắc đủ điều kiện
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Min Personal Volume -->
                        <div>
                            <label for="min_personal_volume" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-chart-line text-purple-500 mr-2"></i>Khối lượng cá nhân tối thiểu
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="min_personal_volume" 
                                       name="min_personal_volume" 
                                       min="0" 
                                       step="0.01"
                                       class="block w-full pr-14 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all duration-200">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold text-sm">PV</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>Khối lượng mua hàng tối thiểu
                            </p>
                        </div>

                        <!-- Active Order Days -->
                        <div>
                            <label for="active_order_days" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-purple-500 mr-2"></i>Số ngày đơn hàng hoạt động
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       id="active_order_days" 
                                       name="active_order_days" 
                                       min="1" 
                                       max="365"
                                       class="block w-full pr-20 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 transition-all duration-200">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-semibold text-sm">ngày</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>Số ngày đơn hàng phải hoạt động
                            </p>
                        </div>

                        <!-- KYC Required -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-check text-purple-500 mr-2"></i>Yêu cầu xác thực
                            </label>
                            <div class="flex items-center h-12 px-4 py-3 border-2 border-gray-300 rounded-xl bg-gray-50">
                                <input type="checkbox" 
                                       id="kyc_required" 
                                       name="kyc_required"
                                       class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-4 cursor-pointer">
                                <label for="kyc_required" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
                                    Yêu cầu KYC
                                </label>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>Bật xác thực danh tính
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button type="submit" 
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Lưu cấu hình
                    </button>
                    <button type="button" 
                            onclick="loadCurrentConfig()"
                            class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <i class="fas fa-refresh mr-2"></i>
                        Tải lại cấu hình
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm mx-4">
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-blue-600 text-5xl mb-4"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Đang xử lý...</h3>
            <p class="text-gray-600">Vui lòng đợi trong giây lát</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let commissionLevelCount = 0;

$(document).ready(function() {
    loadCurrentConfig();
});

function loadCurrentConfig() {
    showLoading();

    $.ajax({
        url: '{{ route("admin.config.index") }}',
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        success: function(response) {
            hideLoading();
            populateForm(response.config);
        },
        error: function(xhr) {
            hideLoading();
            Swal.fire({
                icon: 'error',
                title: 'Không thể tải cấu hình',
                text: 'Có lỗi xảy ra khi tải cấu hình hiện tại. Vui lòng thử lại.',
                confirmButtonText: 'Thử lại',
                showCancelButton: true,
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    loadCurrentConfig();
                }
            });
        }
    });
}

function populateForm(config) {
    $('#width').val(config.width || 10);
    $('#max_depth').val(config.max_depth || 1);
    $('#placement_mode').val(config.placement_mode || 'forced');
    $('#spillover_mode').val(config.spillover_mode || 'bfs');
    $('#capping_per_cycle').val(config.capping_per_cycle || 10000000);
    $('#cycle_period').val(config.cycle_period || 'weekly');
    $('#min_personal_volume').val(config.min_personal_volume || 0);
    $('#active_order_days').val(config.active_order_days || 30);
    $('#kyc_required').prop('checked', config.kyc_required || false);

    // Populate commission levels
    if (config.commissions) {
        $('#commissionLevels').empty();
        commissionLevelCount = 0;
        Object.keys(config.commissions).forEach(level => {
            addCommissionLevel(config.commissions[level] * 100); // Convert to percentage
        });
    }
}

function addCommissionLevel(value = 10) {
    commissionLevelCount++;
    const levelHtml = `
        <div class="commission-level" id="level-${commissionLevelCount}">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-24">
                    <span class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 text-sm font-semibold rounded-lg">
                        <i class="fas fa-layer-group mr-2"></i>Tầng ${commissionLevelCount}
                    </span>
                </div>
                <div class="flex-1 relative">
                    <input type="number" 
                           name="commissions[${commissionLevelCount}]" 
                           value="${value}" 
                           min="0" 
                           max="100" 
                           step="0.01"
                           placeholder="Nhập tỷ lệ..." 
                           class="block w-full pr-14 px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-green-100 focus:border-green-500 transition-all duration-200"
                           required>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <span class="text-gray-500 font-semibold text-sm">%</span>
                    </div>
                </div>
                <button type="button" 
                        onclick="removeCommissionLevel(${commissionLevelCount})"
                        class="flex-shrink-0 p-3 bg-red-500 hover:bg-red-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    $('#commissionLevels').append(levelHtml);
}

function removeCommissionLevel(level) {
    $(`#level-${level}`).fadeOut(300, function() {
        $(this).remove();
    });
}

function resetConfig() {
    Swal.fire({
        title: 'Đặt lại cấu hình mặc định?',
        html: '<p class="text-gray-600">Tất cả các thay đổi hiện tại sẽ bị mất và hệ thống sẽ trở về cấu hình ban đầu.</p>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-undo mr-2"></i> Đặt lại',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Hủy',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();

            $.ajax({
                url: '{{ route("admin.config.reset") }}',
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    hideLoading();
                    populateForm(response.config);
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt lại thành công!',
                        text: 'Cấu hình đã được đặt lại về mặc định.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể đặt lại cấu hình',
                        text: 'Có lỗi xảy ra trong quá trình đặt lại. Vui lòng thử lại.'
                    });
                }
            });
        }
    });
}

$('#configForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const configData = {};

    // Collect form data
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('commissions[')) {
            const levelMatch = key.match(/commissions\[(\d+)\]/);
            if (levelMatch) {
                if (!configData.commissions) configData.commissions = {};
                const percentValue = parseFloat(value);
                if (isNaN(percentValue) || percentValue < 0 || percentValue > 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Dữ liệu không hợp lệ',
                        text: `Tỷ lệ hoa hồng tầng ${levelMatch[1]} phải từ 0 đến 100%.`
                    });
                    return;
                }
                configData.commissions[levelMatch[1]] = percentValue / 100; // Convert to decimal
            }
        } else {
            const numValue = parseFloat(value);
            configData[key] = !isNaN(numValue) ? numValue : value;
        }
    }

    // Handle checkbox
    configData.kyc_required = $('#kyc_required').is(':checked');

    // Show confirmation
    Swal.fire({
        title: 'Xác nhận lưu cấu hình?',
        html: `
            <div class="text-left p-4">
                <p class="mb-3 font-semibold">Cấu hình sẽ được áp dụng:</p>
                <ul class="space-y-2 text-sm">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Độ rộng ma trận: <strong>${configData.width}</strong></li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Độ sâu tối đa: <strong>${configData.max_depth}</strong></li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Giới hạn hoa hồng: <strong>$${configData.capping_per_cycle}</strong></li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Tầng hoa hồng: <strong>${Object.keys(configData.commissions || {}).length}</strong></li>
                </ul>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save mr-2"></i> Lưu cấu hình',
        cancelButtonText: '<i class="fas fa-times mr-2"></i> Hủy',
        confirmButtonColor: '#3b82f6'
    }).then((result) => {
        if (result.isConfirmed) {
            performSave(configData);
        }
    });
});

function performSave(configData) {
    showLoading();

    $.ajax({
        url: '{{ route("admin.config.update") }}',
        method: 'POST',
        data: JSON.stringify(configData),
        contentType: 'application/json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                icon: 'success',
                title: 'Lưu thành công!',
                text: 'Cấu hình đã được cập nhật và áp dụng cho hệ thống.',
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                loadCurrentConfig();
            }, 2000);
        },
        error: function(xhr) {
            hideLoading();
            const error = xhr.responseJSON;

            if (error && error.errors) {
                let errorMessage = '<div class="text-left p-4"><p class="mb-2 font-semibold">Vui lòng kiểm tra lại:</p><ul class="space-y-1 text-sm">';
                Object.values(error.errors).forEach(errors => {
                    errors.forEach(error => errorMessage += `<li><i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>${error}</li>`);
                });
                errorMessage += '</ul></div>';

                Swal.fire({
                    icon: 'error',
                    title: 'Thông tin không hợp lệ',
                    html: errorMessage
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể lưu cấu hình',
                    text: 'Có lỗi xảy ra trong quá trình lưu. Vui lòng thử lại.'
                });
            }
        }
    });
}

function showLoading() {
    document.getElementById('loadingModal').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingModal').classList.add('hidden');
}
</script>
@endpush
