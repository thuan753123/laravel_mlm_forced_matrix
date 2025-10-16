@extends('layouts.app')

@section('title', 'Cấu hình MLM')

@push('styles')
<style>
    .config-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .main-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 25px 30px;
    }

    .section-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin-bottom: 25px;
    }

    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .section-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 25px;
        margin: 0;
        border-radius: 15px 15px 0 0;
        font-size: 1.1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control {
        border: 2px solid #e1e8ed;
        border-radius: 10px;
        padding: 12px 18px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }

    .form-control:hover {
        border-color: #667eea;
    }

    .input-group-text {
        background: #667eea;
        color: white;
        border: none;
        border-radius: 10px 0 0 10px;
        font-weight: 600;
    }

    .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 12px 25px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 0.85rem;
    }

    .commission-level {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border: 2px solid #e1e8ed;
        transition: all 0.3s ease;
    }

    .commission-level:hover {
        border-color: #667eea;
        background: linear-gradient(135deg, #f0f2ff 0%, #e3e7ff 100%);
    }

    .add-commission-btn {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .add-commission-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    }

    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 5px;
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    .form-check-label {
        font-weight: 500;
        color: #2c3e50;
    }

    .action-buttons {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-top: 20px;
    }

    .loading-modal .modal-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        border: none;
    }

    .loading-spinner {
        color: #667eea;
    }

    .success-message {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .error-message {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    }

    .info-box {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        padding: 15px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
    }

    @media (max-width: 768px) {
        .config-container {
            padding: 10px 0;
        }

        .section-body {
            padding: 20px;
        }

        .card-header {
            padding: 20px;
        }

        .section-header {
            padding: 15px 20px;
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="config-container">
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="row">
            <div class="col-12">
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="row">
            <div class="col-12">
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                </div>
            </div>
        </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="main-card">
                    <div class="card-header">
                        <h2 class="mb-0">
                            <i class="fas fa-cog"></i> Cấu hình Hệ thống MLM
                        </h2>
                        <div class="card-tools">
                            <button type="button" class="btn btn-light btn-sm" onclick="resetConfig()" style="background: rgba(255,255,255,0.2); color: white; border: none;">
                                <i class="fas fa-undo"></i> Đặt lại mặc định
                            </button>
                        </div>
                    </div>

                    <form id="configForm">
                        <div class="card-body">
                            <div class="row">
                                <!-- Matrix Configuration -->
                                <div class="col-lg-6">
                                    <div class="section-card">
                                        <h4 class="section-header">
                                            <i class="fas fa-project-diagram"></i> Cấu hình Ma trận
                                        </h4>
                                        <div class="section-body">
                                            <div class="form-group">
                                                <label for="width" class="form-label">
                                                    <i class="fas fa-arrows-alt-h"></i> Độ rộng ma trận
                                                </label>
                                                <input type="number" class="form-control" id="width" name="width" min="1" max="10" required>
                                                <small class="form-text">
                                                    <i class="fas fa-info-circle"></i> Số vị trí trực tiếp tối đa (1-10)
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="max_depth" class="form-label">
                                                    <i class="fas fa-layer-group"></i> Độ sâu tối đa
                                                </label>
                                                <input type="number" class="form-control" id="max_depth" name="max_depth" min="1" max="20" required>
                                                <small class="form-text">
                                                    <i class="fas fa-info-circle"></i> Số tầng tối đa trong ma trận (1-20)
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="placement_mode" class="form-label">
                                                    <i class="fas fa-map-marker-alt"></i> Chế độ đặt vị trí
                                                </label>
                                                <select class="form-control" id="placement_mode" name="placement_mode" required>
                                                    <option value="forced">Ép buộc</option>
                                                    <option value="auto">Tự động</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="spillover_mode" class="form-label">
                                                    <i class="fas fa-sitemap"></i> Chế độ spillover
                                                </label>
                                                <select class="form-control" id="spillover_mode" name="spillover_mode" required>
                                                    <option value="bfs">Breadth First (BFS)</option>
                                                    <option value="balanced">Cân bằng</option>
                                                    <option value="leftmost">Trái nhất</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Commission Configuration -->
                                <div class="col-lg-6">
                                    <div class="section-card">
                                        <h4 class="section-header">
                                            <i class="fas fa-coins"></i> Cấu hình Hoa hồng
                                        </h4>
                                        <div class="section-body">
                                            <div class="form-group">
                                                <label for="capping_per_cycle" class="form-label">
                                                    <i class="fas fa-money-bill-wave"></i> Giới hạn hoa hồng mỗi chu kỳ
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="number" class="form-control" id="capping_per_cycle" name="capping_per_cycle" min="0" step="0.01" required>
                                                </div>
                                                <small class="form-text">
                                                    <i class="fas fa-info-circle"></i> Giới hạn hoa hồng tối đa mỗi chu kỳ
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="cycle_period" class="form-label">
                                                    <i class="fas fa-calendar-alt"></i> Chu kỳ hoa hồng
                                                </label>
                                                <select class="form-control" id="cycle_period" name="cycle_period" required>
                                                    <option value="daily">Hàng ngày</option>
                                                    <option value="weekly">Hàng tuần</option>
                                                    <option value="monthly">Hàng tháng</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-percentage"></i> Hoa hồng theo tầng (%)
                                                </label>
                                                <div id="commissionLevels">
                                                    <!-- Commission levels will be dynamically added here -->
                                                </div>
                                                <button type="button" class="add-commission-btn" onclick="addCommissionLevel()">
                                                    <i class="fas fa-plus"></i> Thêm tầng hoa hồng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Qualification Rules -->
                            <div class="col-12">
                                <div class="section-card">
                                    <h4 class="section-header">
                                        <i class="fas fa-clipboard-check"></i> Quy tắc đủ điều kiện
                                    </h4>
                                    <div class="section-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="min_personal_volume" class="form-label">
                                                        <i class="fas fa-chart-line"></i> Khối lượng cá nhân tối thiểu
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="min_personal_volume" name="min_personal_volume" min="0" step="0.01">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">PV</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text">
                                                        <i class="fas fa-info-circle"></i> Khối lượng mua hàng tối thiểu để đủ điều kiện
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="active_order_days" class="form-label">
                                                        <i class="fas fa-clock"></i> Số ngày đơn hàng hoạt động
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" id="active_order_days" name="active_order_days" min="1" max="365">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">ngày</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text">
                                                        <i class="fas fa-info-circle"></i> Số ngày đơn hàng phải hoạt động để đủ điều kiện
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" class="form-check-input" id="kyc_required" name="kyc_required" style="transform: scale(1.2);">
                                                        <label class="form-check-label" for="kyc_required" style="font-weight: 600; color: #2c3e50; margin-left: 10px;">
                                                            <i class="fas fa-user-check"></i> Yêu cầu KYC
                                                        </label>
                                                    </div>
                                                    <small class="form-text">
                                                        <i class="fas fa-info-circle"></i> Bật xác thực danh tính để đủ điều kiện hoa hồng
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12">
                                <div class="action-buttons">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                                <i class="fas fa-save"></i> Lưu cấu hình
                                            </button>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="loadCurrentConfig()">
                                                <i class="fas fa-refresh"></i> Tải lại cấu hình
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content loading-modal">
            <div class="modal-body text-center">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                </div>
                <h5 class="mb-0">Đang xử lý...</h5>
                <p class="text-muted mt-2">Vui lòng đợi trong giây lát</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let commissionLevelCount = 1;

$(document).ready(function() {
    loadCurrentConfig();
    initializeCommissionLevels();

    // Add smooth animations
    $('.section-card').addClass('animate__animated animate__fadeInUp');

    // Form validation enhancement
    $('input, select').on('focus', function() {
        $(this).closest('.form-group').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.form-group').removeClass('focused');
    });
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

            // Animate form population
            $('.form-control, .form-check-input').addClass('animate__animated animate__pulse');
            setTimeout(() => {
                $('.form-control, .form-check-input').removeClass('animate__animated animate__pulse');
            }, 1000);
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
    // Animate form clearing
    $('.form-control').addClass('animate__animated animate__fadeOut');
    $('.form-check-input').addClass('animate__animated animate__fadeOut');

    setTimeout(() => {
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
                addCommissionLevel(config.commissions[level]);
            });
        }

        // Animate form population
        $('.form-control, .form-check-input').removeClass('animate__animated animate__fadeOut').addClass('animate__animated animate__fadeIn');
        setTimeout(() => {
            $('.form-control, .form-check-input').removeClass('animate__animated animate__fadeIn');
        }, 500);
    }, 300);
}

function addCommissionLevel(value = 0.1) {
    commissionLevelCount++;
    const levelHtml = `
        <div class="commission-level animate__animated animate__fadeInUp" id="level-${commissionLevelCount}">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-layer-group"></i> Tầng ${commissionLevelCount}
                    </span>
                </div>
                <input type="number" class="form-control" name="commissions[${commissionLevelCount}]" value="${value}" min="0" max="1" step="0.01" placeholder="Nhập tỷ lệ hoa hồng..." required>
                <div class="input-group-append">
                    <span class="input-group-text">%</span>
                    <button class="btn btn-danger" type="button" onclick="removeCommissionLevel(${commissionLevelCount})" title="Xóa tầng này">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    $('#commissionLevels').append(levelHtml);
}

function removeCommissionLevel(level) {
    $(`#level-${level}`).addClass('animate__animated animate__fadeOutLeft');
    setTimeout(() => {
        $(`#level-${level}`).remove();
    }, 300);
}

function initializeCommissionLevels() {
    // Add initial level if none exist
    if ($('#commissionLevels').children().length === 0) {
        addCommissionLevel();
    }
}

function resetConfig() {
    Swal.fire({
        title: 'Đặt lại cấu hình mặc định?',
        html: `
            <p class="text-muted">Tất cả các thay đổi hiện tại sẽ bị mất và hệ thống sẽ trở về cấu hình ban đầu.</p>
            <div class="text-left mt-3">
                <small class="text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Lưu ý: Hành động này không thể hoàn tác.
                </small>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-undo"></i> Đặt lại',
        cancelButtonText: '<i class="fas fa-times"></i> Hủy',
        reverseButtons: true,
        customClass: {
            popup: 'animate__animated animate__zoomIn',
            confirmButton: 'animate__animated animate__pulse',
            cancelButton: 'animate__animated animate__pulse'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();

            $.ajax({
                url: '{{ route("admin.config.reset") }}',
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    hideLoading();
                    populateForm(response.config);

                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt lại thành công!',
                        html: 'Cấu hình đã được đặt lại về mặc định.',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        customClass: {
                            popup: 'animate__animated animate__bounceIn'
                        }
                    });
                },
                error: function(xhr) {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Không thể đặt lại cấu hình',
                        text: 'Có lỗi xảy ra trong quá trình đặt lại. Vui lòng thử lại.',
                        confirmButtonText: 'Thử lại'
                    });
                }
            });
        }
    });
}

$('#configForm').on('submit', function(e) {
    e.preventDefault();

    // Validate form before submission
    const form = this;
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        Swal.fire({
            icon: 'warning',
            title: 'Thông tin chưa đầy đủ',
            text: 'Vui lòng điền đầy đủ thông tin bắt buộc.',
            confirmButtonText: 'Đã hiểu'
        });
        return;
    }

    const formData = new FormData(this);
    const configData = {};

    // Collect form data with better error handling
    for (let [key, value] of formData.entries()) {
        if (key.startsWith('commissions[')) {
            // Handle commission levels
            const levelMatch = key.match(/commissions\[(\d+)\]/);
            if (levelMatch) {
                if (!configData.commissions) configData.commissions = {};
                const numValue = parseFloat(value);
                if (isNaN(numValue) || numValue < 0 || numValue > 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Dữ liệu không hợp lệ',
                        text: `Tỷ lệ hoa hồng tầng ${levelMatch[1]} phải từ 0 đến 1.`,
                        confirmButtonText: 'Sửa lại'
                    });
                    return;
                }
                configData.commissions[levelMatch[1]] = numValue;
            }
        } else {
            const numValue = parseFloat(value);
            configData[key] = !isNaN(numValue) ? numValue : value;
        }
    }

    // Handle checkbox
    configData.kyc_required = $('#kyc_required').is(':checked');

    // Show confirmation before saving
    Swal.fire({
        title: 'Xác nhận lưu cấu hình?',
        html: `
            <div class="text-left">
                <p class="mb-2"><strong>Cấu hình sẽ được áp dụng:</strong></p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-check text-success"></i> Độ rộng ma trận: ${configData.width}</li>
                    <li><i class="fas fa-check text-success"></i> Độ sâu tối đa: ${configData.max_depth}</li>
                    <li><i class="fas fa-check text-success"></i> Giới hạn hoa hồng: $${configData.capping_per_cycle}</li>
                    <li><i class="fas fa-check text-success"></i> Tầng hoa hồng: ${Object.keys(configData.commissions || {}).length}</li>
                </ul>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-save"></i> Lưu cấu hình',
        cancelButtonText: '<i class="fas fa-times"></i> Hủy',
        confirmButtonColor: '#28a745',
        customClass: {
            popup: 'animate__animated animate__zoomIn'
        }
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
            'Accept': 'application/json'
        },
        success: function(response) {
            hideLoading();

            // Show success animation
            Swal.fire({
                icon: 'success',
                title: 'Lưu thành công!',
                html: `
                    <div class="animate__animated animate__bounceIn">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <p>Cấu hình đã được cập nhật và áp dụng cho hệ thống.</p>
                    </div>
                `,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'animate__animated animate__zoomIn'
                }
            });

            // Refresh form data
            setTimeout(() => {
                loadCurrentConfig();
            }, 2000);
        },
        error: function(xhr) {
            hideLoading();
            const error = xhr.responseJSON;

            if (error && error.errors) {
                let errorMessage = '<div class="text-left"><p class="mb-2"><strong>Vui lòng kiểm tra lại:</strong></p><ul class="list-unstyled">';
                Object.values(error.errors).forEach(errors => {
                    errors.forEach(error => errorMessage += `<li><i class="fas fa-exclamation-triangle text-warning"></i> ${error}</li>`);
                });
                errorMessage += '</ul></div>';

                Swal.fire({
                    icon: 'error',
                    title: 'Thông tin không hợp lệ',
                    html: errorMessage,
                    confirmButtonText: 'Sửa lại',
                    customClass: {
                        popup: 'animate__animated animate__shakeX'
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể lưu cấu hình',
                    text: 'Có lỗi xảy ra trong quá trình lưu. Vui lòng thử lại.',
                    confirmButtonText: 'Thử lại',
                    showCancelButton: true,
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performSave(configData);
                    }
                });
            }
        }
    });
}

function showLoading() {
    $('#loadingModal').modal({
        backdrop: 'static',
        keyboard: false
    }).modal('show');
}

function hideLoading() {
    $('#loadingModal').modal('hide');
}

// Enhanced tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

// Auto-save draft (optional feature)
let autoSaveTimer;
function enableAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        console.log('Auto-saving draft...');
        // Implement auto-save logic here if needed
    }, 30000); // Auto-save every 30 seconds
}

// Enable auto-save when form changes
$('#configForm').on('input change', 'input, select, textarea', function() {
    enableAutoSave();
});
</script>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

<!-- Animate.css for animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush
