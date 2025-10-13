<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-xl font-bold text-gray-800">
                                <?php echo e(__('ui.title')); ?>

                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                                <?php echo e(__('ui.nav.dashboard')); ?>

                            </a>
                            <a href="<?php echo e(route('matrix.index')); ?>" class="nav-link <?php echo e(request()->routeIs('matrix.*') ? 'active' : ''); ?>">
                                <?php echo e(__('ui.nav.matrix')); ?>

                            </a>
                            <a href="<?php echo e(route('orders.index')); ?>" class="nav-link <?php echo e(request()->routeIs('orders.*') ? 'active' : ''); ?>">
                                <?php echo e(__('ui.nav.orders')); ?>

                            </a>
                            <a href="<?php echo e(route('commissions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('commissions.*') ? 'active' : ''); ?>">
                                <?php echo e(__('ui.nav.commissions')); ?>

                            </a>
                            <?php if(auth()->guard()->check()): ?>
                                <?php if(auth()->user()->canAccessAdmin()): ?>
                                    <a href="<?php echo e(route('admin.config.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>">
                                        <?php echo e(__('ui.nav.config')); ?>

                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Right side -->
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <?php if(auth()->guard()->check()): ?>
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-700">
                                        <?php echo e(auth()->user()->fullname ?? auth()->user()->email); ?>

                                    </span>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline" id="logout-form">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                            <?php echo e(__('ui.nav.logout')); ?>

                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex items-center space-x-4">
                                <a href="<?php echo e(route('login')); ?>" class="text-sm text-gray-500 hover:text-gray-700">
                                    <?php echo e(__('ui.auth.login')); ?>

                                </a>
                                <a href="<?php echo e(route('register')); ?>" class="text-sm text-gray-500 hover:text-gray-700">
                                    <?php echo e(__('ui.auth.register')); ?>

                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button type="button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon -->
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="sm:hidden" id="mobile-menu">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="<?php echo e(route('dashboard')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                        <?php echo e(__('ui.nav.dashboard')); ?>

                    </a>
                    <a href="<?php echo e(route('matrix.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('matrix.*') ? 'active' : ''); ?>">
                        <?php echo e(__('ui.nav.matrix')); ?>

                    </a>
                    <a href="<?php echo e(route('orders.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('orders.*') ? 'active' : ''); ?>">
                        <?php echo e(__('ui.nav.orders')); ?>

                    </a>
                    <a href="<?php echo e(route('commissions.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('commissions.*') ? 'active' : ''); ?>">
                        <?php echo e(__('ui.nav.commissions')); ?>

                    </a>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->canAccessAdmin()): ?>
                            <a href="<?php echo e(route('admin.config.index')); ?>" class="mobile-nav-link <?php echo e(request()->routeIs('admin.*') ? 'active' : ''); ?>">
                                <?php echo e(__('ui.nav.config')); ?>

                            </a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="block" id="mobile-logout-form">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="mobile-nav-link w-full text-left">
                                <?php echo e(__('ui.nav.logout')); ?>

                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <style>
        .nav-link {
            @apply inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition duration-150 ease-in-out;
        }
        .nav-link.active {
            @apply border-indigo-500 text-gray-900;
        }
        .mobile-nav-link {
            @apply block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition duration-150 ease-in-out;
        }
        .mobile-nav-link.active {
            @apply bg-indigo-50 border-indigo-500 text-indigo-700;
        }
    </style>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Handle logout form submission
        function handleLogout(formId) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                try {
                    const response = await fetch('<?php echo e(route("logout")); ?>', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Clear all cookies and redirect to login
                        document.cookie.split(";").forEach(function(c) {
                            document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
                        });

                        // Clear local storage if exists
                        localStorage.clear();
                        sessionStorage.clear();

                        // Redirect to login page
                        window.location.href = '/login';
                    } else {
                        alert(data.message || 'Đăng xuất thất bại');
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                    // Force redirect anyway
                    window.location.href = '/login';
                }
            });
        }

        // Handle both desktop and mobile logout forms
        handleLogout('logout-form');
        handleLogout('mobile-logout-form');
    </script>
</body>
</html><?php /**PATH /Users/kentpc/Documents/GitHub/laravel_mlm_forced_matrix/mlm-matrix/resources/views/layouts/app.blade.php ENDPATH**/ ?>