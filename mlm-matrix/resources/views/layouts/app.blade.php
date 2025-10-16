<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('ui.title') }} - Hệ thống quản lý đại lý</title>

    <!-- Meta tags for social sharing -->
    <meta name="description" content="Hệ thống quản lý đại lý AI VN168 - Quản lý ma trận, đơn hàng và hoa hồng">
    <meta name="keywords" content="đại lý AI, VN168, quản lý ma trận, MLM, hoa hồng">
    <meta name="author" content="AI VN168">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ __('ui.title') }} - Hệ thống quản lý đại lý">
    <meta property="og:description" content="Hệ thống quản lý đại lý AI VN168 - Quản lý ma trận, đơn hàng và hoa hồng">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:site_name" content="{{ __('ui.title') }}">
    <meta property="og:locale" content="vi_VN">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ __('ui.title') }} - Hệ thống quản lý đại lý">
    <meta property="twitter:description" content="Hệ thống quản lý đại lý AI VN168 - Quản lý ma trận, đơn hàng và hoa hồng">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">
    
    <!-- Zalo -->
    <meta property="zalo:title" content="{{ __('ui.title') }} - Hệ thống quản lý đại lý">
    <meta property="zalo:description" content="Hệ thống quản lý đại lý AI VN168 - Quản lý ma trận, đơn hàng và hoa hồng">
    <meta property="zalo:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Sidebar Navigation Styles -->
    <link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">
    
    <!-- Page-specific styles -->
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        @auth
        <!-- Sidebar -->
        <div id="sidebar">
            <!-- Logo -->
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <span id="sidebar-title">{{ __('ui.title') }}</span>
                </a>
                <!-- Toggle Button -->
                <button id="sidebar-toggle" type="button" aria-label="Toggle Sidebar">
                    <svg id="sidebar-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <div class="sidebar-nav">
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-tooltip="{{ __('ui.nav.dashboard') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        <span>{{ __('ui.nav.dashboard') }}</span>
                    </a>
                    <a href="{{ route('matrix.index') }}" class="sidebar-link {{ request()->routeIs('matrix.*') ? 'active' : '' }}" data-tooltip="{{ __('ui.nav.matrix') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2H8V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2h-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2H8v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2h-2v-2z"></path>
                        </svg>
                        <span>{{ __('ui.nav.matrix') }}</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" data-tooltip="{{ __('ui.nav.orders') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                        </svg>
                        <span>{{ __('ui.nav.orders') }}</span>
                    </a>
                    <a href="{{ route('commissions.index') }}" class="sidebar-link {{ request()->routeIs('commissions.*') ? 'active' : '' }}" data-tooltip="{{ __('ui.nav.commissions') }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <span>{{ __('ui.nav.commissions') }}</span>
                    </a>
                    @if(auth()->user()->canAccessAdmin())
                        <!-- Admin Menu with Submenu -->
                        <div class="admin-menu-section">
                            <div class="sidebar-divider">
                                <span>QUẢN TRỊ</span>
                            </div>

                            <a href="{{ route('admin.config.index') }}" class="sidebar-link {{ request()->routeIs('admin.config.*') ? 'active' : '' }}" data-tooltip="Cấu hình MLM">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Cấu hình MLM</span>
                            </a>

                            <a href="{{ route('admin.policies.index') }}" class="sidebar-link {{ request()->routeIs('admin.policies.*') ? 'active' : '' }}" data-tooltip="Quản lý Chính sách">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Quản lý Chính sách</span>
                            </a>

                            <a href="{{ route('admin.commissions.index') }}" class="sidebar-link {{ request()->routeIs('admin.commissions.*') ? 'active' : '' }}" data-tooltip="Quản lý Hoa hồng">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Quản lý Hoa hồng</span>
                            </a>
                        </div>
                    @endif
                </nav>
            </div>

            <!-- User Info & Logout -->
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-user-avatar">
                        <span>{{ substr(auth()->user()->fullname ?? auth()->user()->email, 0, 1) }}</span>
                    </div>
                    <div class="sidebar-user-info">
                        <p>{{ auth()->user()->fullname ?? auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>{{ __('ui.nav.logout') }}</span>
                    </button>
                </form>
            </div>
        </div>
        @endauth

        <!-- Page Content -->
        <main id="main-content" class="flex-1 bg-gray-100 transition-all duration-300 ease-in-out">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        // Handle logout form submission
        function handleLogout(formId) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                try {
                    const response = await fetch('{{ route("logout") }}', {
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

        @auth
        // Sidebar toggle functionality
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarTitle = document.getElementById('sidebar-title');
        const mainContent = document.getElementById('main-content');

        // Utility functions
        const saveSidebarState = (isCollapsed) => {
            localStorage.setItem('sidebar-collapsed', isCollapsed.toString());
        };

        const getSidebarState = () => {
            return localStorage.getItem('sidebar-collapsed') === 'true';
        };

        const updateSidebarWidth = () => {
            const isMobile = window.innerWidth <= 768;

            if (isMobile) {
                // On mobile, use full width layout
                sidebar.style.width = '100%';
                sidebar.style.minWidth = '100%';
                if (mainContent) {
                    mainContent.style.marginLeft = '0';
                }
            } else {
                // On desktop, use sidebar layout
                if (sidebar.classList.contains('collapsed')) {
                    sidebar.style.width = '64px';
                    sidebar.style.minWidth = '64px';
                    if (mainContent) {
                        mainContent.style.marginLeft = '64px';
                    }
                } else {
                    sidebar.style.width = '256px';
                    sidebar.style.minWidth = '256px';
                    if (mainContent) {
                        mainContent.style.marginLeft = '256px';
                    }
                }
            }
        };

        // Initialize sidebar state
        const initializeSidebar = () => {
            const isCollapsed = getSidebarState();
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
            }
            updateSidebarWidth();
        };

        // Toggle sidebar function
        const toggleSidebar = () => {
            const isCollapsed = sidebar.classList.contains('collapsed');

            if (isCollapsed) {
                sidebar.classList.remove('collapsed');
                saveSidebarState(false);
            } else {
                sidebar.classList.add('collapsed');
                saveSidebarState(true);
            }

            // Update width after transition
            setTimeout(updateSidebarWidth, 50);
        };

        // Handle window resize
        const handleResize = () => {
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                // On mobile, always use full width
                sidebar.style.width = '100%';
                sidebar.style.minWidth = '100%';
                if (mainContent) {
                    mainContent.style.marginLeft = '0';
                }
            } else {
                // On desktop, use saved state
                updateSidebarWidth();
            }
        };

        // Close sidebar when clicking outside on mobile
        const handleOutsideClick = (event) => {
            const isMobile = window.innerWidth <= 768;
            if (isMobile && !sidebar.contains(event.target) && sidebarToggle && !sidebarToggle.contains(event.target)) {
                if (!sidebar.classList.contains('collapsed')) {
                    sidebar.classList.add('collapsed');
                    saveSidebarState(true);
                    setTimeout(updateSidebarWidth, 50);
                }
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            initializeSidebar();

            // Add event listeners
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            document.addEventListener('click', handleOutsideClick);
            window.addEventListener('resize', handleResize);

            // Update active states after page load
            setTimeout(() => {
                const activeLinks = document.querySelectorAll('.sidebar-link.active');
                activeLinks.forEach(link => {
                    // Ensure active state is properly applied
                    if (sidebar.classList.contains('collapsed')) {
                        link.classList.add('active');
                    }
                });
            }, 100);
        });
        @endauth
    </script>
</body>
</html>