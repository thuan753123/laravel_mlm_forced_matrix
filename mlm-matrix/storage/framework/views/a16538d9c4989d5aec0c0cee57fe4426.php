<?php $__env->startSection('title', 'Quản lý Chính sách'); ?>

<?php $__env->startPush('styles'); ?>
<link href="<?php echo e(asset('css/admin/admin-common.css')); ?>" rel="stylesheet">
<link href="<?php echo e(asset('css/admin/policies.css')); ?>" rel="stylesheet">
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
                    <h2 class="mb-0"><i class="fas fa-file-contract"></i> Quản lý Chính sách</h2>
                    <p class="text-muted mb-0">Quản lý tất cả chính sách hệ thống</p>
                </div>
                <a href="<?php echo e(route('admin.policies.create')); ?>" class="btn btn-gradient">
                    <i class="fas fa-plus"></i> Tạo chính sách mới
                </a>
            </div>
        </div>

        <div class="table-card">
            <div class="policy-table-wrapper">
                <table class="table table-hover policy-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên chính sách</th>
                            <th>Giá trị</th>
                            <th>Loại</th>
                            <th>Cập nhật</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $policies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $policy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="fade-in-up">
                            <td><strong>#<?php echo e($policy->id); ?></strong></td>
                            <td>
                                <span class="policy-key"><?php echo e(str_replace('policy_', '', $policy->key)); ?></span>
                            </td>
                            <td>
                                <span class="policy-value"><?php echo e(\Illuminate\Support\Str::limit($policy->value, 50)); ?></span>
                            </td>
                            <td>
                                <span class="type-badge <?php echo e($policy->type); ?>"><?php echo e($policy->type); ?></span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="far fa-clock"></i> <?php echo e($policy->updated_at->diffForHumans()); ?>

                                </small>
                            </td>
                            <td class="text-center">
                                <div class="policy-actions">
                                    <a href="<?php echo e(route('admin.policies.edit', $policy->id)); ?>" 
                                       class="btn btn-sm btn-info shadow-hover" 
                                       title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.policies.destroy', $policy->id)); ?>" 
                                          method="POST" 
                                          class="delete-form" 
                                          onsubmit="return confirm('⚠️ Bạn có chắc chắn muốn xóa chính sách này?\n\nHành động này không thể hoàn tác!');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger shadow-hover" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h5>Chưa có chính sách nào</h5>
                                    <p>Bắt đầu bằng cách tạo chính sách đầu tiên của bạn</p>
                                    <a href="<?php echo e(route('admin.policies.create')); ?>" class="btn btn-gradient">
                                        <i class="fas fa-plus"></i> Tạo chính sách đầu tiên
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($policies->hasPages()): ?>
            <div class="mt-4">
                <?php echo e($policies->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/admin/policies/index.blade.php ENDPATH**/ ?>