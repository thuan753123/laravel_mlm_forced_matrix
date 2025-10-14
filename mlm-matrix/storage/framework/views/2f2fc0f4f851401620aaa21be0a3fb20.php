<?php $__env->startSection('title', 'Quản lý Hoa hồng'); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/admin/admin-common.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/admin/commissions.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="admin-container">
    <div class="container-fluid">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-coins"></i> Quản lý Chính sách Hoa hồng</h2>
                    <p class="text-muted mb-0">Quản lý các mẫu hoa hồng cho hệ thống MLM</p>
                </div>
                <a href="<?php echo e(route('admin.commissions.create')); ?>" class="btn btn-gradient">
                    <i class="fas fa-plus"></i> Tạo chính sách mới
                </a>
            </div>
        </div>

        <div class="commission-grid">
            <?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $data = json_decode($commission->value, true);
                ?>
                
                <div class="commission-card fade-in-up">
                    <div class="commission-card-header">
                        <h4>
                            <i class="fas fa-coins"></i> 
                            <?php echo e($data['name'] ?? 'Chưa đặt tên'); ?>

                        </h4>
                        <div class="commission-meta">
                            <span>
                                <i class="fas fa-info-circle"></i> 
                                <?php echo e($data['description'] ?? 'Không có mô tả'); ?>

                            </span>
                            <span>
                                <i class="fas fa-layer-group"></i> 
                                <?php echo e(count($data['levels'] ?? [])); ?> tầng
                            </span>
                        </div>
                    </div>

                    <div class="commission-card-body">
                        <div class="commission-levels">
                            <?php if(isset($data['levels']) && is_array($data['levels'])): ?>
                                <?php $__currentLoopData = $data['levels']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="commission-level">
                                    <div class="level-info">
                                        <div class="level-number"><?php echo e($index + 1); ?></div>
                                        <div class="level-label">Tầng <?php echo e($index + 1); ?></div>
                                    </div>
                                    <div class="level-percentage">
                                        <?php echo e(number_format($level['rate'] * 100, 1)); ?><span class="percent-sign">%</span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="commission-card-footer">
                        <a href="<?php echo e(route('admin.commissions.edit', $commission->id)); ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <form action="<?php echo e(route('admin.commissions.destroy', $commission->id)); ?>" 
                              method="POST" 
                              style="display: inline-block;" 
                              onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa chính sách hoa hồng này?\n\nHành động này không thể hoàn tác!');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="commission-empty-state">
                    <i class="fas fa-coins"></i>
                    <h4>Chưa có chính sách hoa hồng nào</h4>
                    <p>Bắt đầu bằng cách tạo chính sách hoa hồng đầu tiên của bạn</p>
                    <a href="<?php echo e(route('admin.commissions.create')); ?>" class="btn btn-gradient">
                        <i class="fas fa-plus"></i> Tạo chính sách đầu tiên
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if($commissions->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($commissions->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/admin/commissions/index.blade.php ENDPATH**/ ?>