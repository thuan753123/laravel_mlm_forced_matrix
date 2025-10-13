<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <?php echo e(__('ui.auth.register')); ?>

            </h2>
        </div>
        <form class="mt-8 space-y-6" method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="fullname" class="sr-only"><?php echo e(__('ui.auth.fullname')); ?></label>
                    <input id="fullname" name="fullname" type="text" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.fullname')); ?>">
                </div>
                <div>
                    <label for="email" class="sr-only"><?php echo e(__('ui.auth.email')); ?></label>
                    <input id="email" name="email" type="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.email')); ?>">
                </div>
                <div>
                    <label for="phone_number" class="sr-only"><?php echo e(__('ui.auth.phone_number')); ?></label>
                    <input id="phone_number" name="phone_number" type="tel"
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.phone_number')); ?>">
                </div>
                <div>
                    <label for="referral_code" class="sr-only"><?php echo e(__('ui.auth.referral_code')); ?></label>
                    <input id="referral_code" name="referral_code" type="text"
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.referral_code')); ?>">
                </div>
                <div>
                    <label for="password" class="sr-only"><?php echo e(__('ui.auth.password')); ?></label>
                    <input id="password" name="password" type="password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.password')); ?>">
                </div>
                <div>
                    <label for="password_confirmation" class="sr-only"><?php echo e(__('ui.auth.confirm_password')); ?></label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                           placeholder="<?php echo e(__('ui.auth.confirm_password')); ?>">
                </div>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <?php echo e(__('ui.auth.register')); ?>

                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/auth/register.blade.php ENDPATH**/ ?>