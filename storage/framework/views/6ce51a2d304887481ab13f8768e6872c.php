<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $__env->yieldContent('title', 'POS Raula'); ?> - Aplikasi Kasir Digital</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <link rel="icon" type="image/jpg" href="<?php echo e(asset('imagelogo/lopos.jpg')); ?>">

    <style>
        /* Sidebar Collapsed Mode */
        #sidebar.collapsed {
            width: 4.5rem;
        }

        #sidebar.collapsed .sidebar-text {
            display: none;
        }

        #sidebar.collapsed .sidebar-item {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        #main-content.collapsed {
            margin-left: 4.5rem;
        }

        @media (min-width: 1024px) {
            #main-content {
                margin-left: 16rem;
            }

            #main-content.collapsed {
                margin-left: 4.5rem;
            }
        }
    </style>
</head>

<body class="bg-sky-50 dark:bg-gray-950 font-sans antialiased transition-colors duration-300">

    <div class="h-screen flex overflow-hidden">

        
        <?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div id="main-content" class="flex-1 flex flex-col min-w-0 overflow-hidden transition-all duration-300">

            
            <header
                class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 h-[72px] flex items-center justify-between px-4 sm:px-6 sticky top-0 z-20 shadow-sm transition-colors duration-300">

                <div class="flex items-center gap-3">
                    
                    <button id="sidebar-toggle"
                        class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    
                    <button id="sidebar-collapse"
                        class="hidden lg:flex items-center justify-center p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition"
                        title="Collapse / Expand Sidebar">
                        <svg id="icon-expanded" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                        </svg>
                        <svg id="icon-collapsed" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>

                    
                    <div class="flex items-center gap-2.5">
                        <img src="<?php echo e(asset('imagelogo/lopos.jpg')); ?>" alt="Logo"
                            class="w-9 h-9 rounded-xl object-cover shadow-md shadow-indigo-500/25 shrink-0">
                        <div class="leading-tight hidden sm:block">
                            <div class="font-bold text-gray-900 dark:text-white text-[15px]">POS Raula</div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400">Point of Sale</div>
                        </div>
                    </div>

                    
                    <div class="hidden md:block w-px h-8 bg-gray-200 dark:bg-gray-700 mx-1"></div>

                    
                    <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100 tracking-tight">
                        <?php if(request()->is('dashboard')): ?>
                            Dashboard
                        <?php elseif(request()->is('profile*')): ?>
                            Profil Saya
                        <?php elseif(request()->is('admin/users*') || request()->is('users*')): ?>
                            Pengguna
                        <?php elseif(request()->is('produk*')): ?>
                            Produk
                        <?php elseif(request()->is('kategori*')): ?>
                            Kategori Produk
                        <?php elseif(request()->is('pemasok*')): ?>
                            Pemasok
                        <?php elseif(request()->is('pembelian*')): ?>
                            Pembelian
                        <?php elseif(request()->is('laporan*')): ?>
                            Laporan
                        <?php elseif(request()->is('penjualan*')): ?>
                            Penjualan
                        <?php else: ?>
                            <?php echo e(trim($__env->yieldContent('header')) ?: ''); ?>

                        <?php endif; ?>
                    </h2>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">

                    
                    <div class="relative">
                        <button id="notif-btn"
                            class="relative p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full"></span>
                        </button>

                        <div id="notif-dropdown"
                            class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-900 rounded-xl shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-hidden">

                            <?php
                                $notifStokHabis = \App\Models\Produk::where('stok', 0)->orderBy('nama')->limit(5)->get();
                                $notifStokRendah = \App\Models\Produk::where('stok', '>', 0)->where('stok', '<=', 10)->orderBy('stok')->limit(5)->get();
                                $notifCount = $notifStokHabis->count() + $notifStokRendah->count();
                            ?>
                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium"><?php echo e($notifCount); ?> item</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <?php $__empty_1 = true; $__currentLoopData = $notifStokHabis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <a href="<?php echo e(url('/produk')); ?>"
                                    class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <div class="w-9 h-9 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Produk Habis</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo e($p->nama); ?> stok sudah 0</p>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>

                                <?php $__currentLoopData = $notifStokRendah; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(url('/produk')); ?>"
                                    class="flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                    <div class="w-9 h-9 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Stok Rendah</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><?php echo e($p->nama); ?> tersisa <?php echo e($p->stok); ?> pcs</p>
                                    </div>
                                </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <?php if($notifCount === 0): ?>
                                <div class="px-4 py-8 text-center text-sm text-gray-400">
                                    Tidak ada notifikasi
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800 text-center">
                                <a href="#"
                                    class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Lihat Semua Notifikasi
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <button id="theme-toggle" type="button"
                        class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" />
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                        </svg>
                    </button>

                    
                    <div class="hidden md:flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                        <span id="navbar-date"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span>
                        <span class="text-gray-300 dark:text-gray-600">•</span>
                        <span id="navbar-clock" class="font-medium text-indigo-600 dark:text-indigo-400 tabular-nums">
                            --:--:--
                        </span>
                    </div>

                    
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-sm font-medium
                                   text-gray-600 dark:text-gray-300
                                   bg-gray-100 hover:bg-rose-50 dark:bg-gray-800 dark:hover:bg-rose-900/30
                                   hover:text-rose-600 dark:hover:text-rose-400
                                   border border-transparent hover:border-rose-200 dark:hover:border-rose-800
                                   transition shadow-sm"
                            title="Keluar dari aplikasi">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            
            <main class="flex-1 overflow-y-auto">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <script>
        // Dark Mode
        const themeToggleBtn = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('theme-toggle-light-icon');
        const darkIcon = document.getElementById('theme-toggle-dark-icon');

        if (localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            lightIcon?.classList.remove('hidden');
            darkIcon?.classList.add('hidden');
        } else {
            document.documentElement.classList.remove('dark');
            lightIcon?.classList.add('hidden');
            darkIcon?.classList.remove('hidden');
        }

        themeToggleBtn?.addEventListener('click', function() {
            lightIcon?.classList.toggle('hidden');
            darkIcon?.classList.toggle('hidden');

            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        });

        // Sidebar Mobile
        document.getElementById('sidebar-toggle')?.addEventListener('click', function() {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
        });

        // Sidebar Collapse (Desktop)
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const collapseBtn = document.getElementById('sidebar-collapse');
        const iconExpanded = document.getElementById('icon-expanded');
        const iconCollapsed = document.getElementById('icon-collapsed');

        function updateCollapseIcon(isCollapsed) {
            if (isCollapsed) {
                iconExpanded?.classList.add('hidden');
                iconCollapsed?.classList.remove('hidden');
            } else {
                iconExpanded?.classList.remove('hidden');
                iconCollapsed?.classList.add('hidden');
            }
        }

        // Load saved state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar?.classList.add('collapsed');
            mainContent?.classList.add('collapsed');
            updateCollapseIcon(true);
        } else {
            updateCollapseIcon(false);
        }

        collapseBtn?.addEventListener('click', function() {
            sidebar?.classList.toggle('collapsed');
            mainContent?.classList.toggle('collapsed');

            const isCollapsed = sidebar?.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            updateCollapseIcon(isCollapsed);
        });

        // Clock
        function updateNavbarClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            const el = document.getElementById('navbar-clock');
            if (el) el.textContent = timeString + ' WIB';
        }
        updateNavbarClock();
        setInterval(updateNavbarClock, 1000);

        // Notifikasi Dropdown
        const notifBtn = document.getElementById('notif-btn');
        const notifDropdown = document.getElementById('notif-dropdown');

        notifBtn?.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown?.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!notifBtn?.contains(e.target) && !notifDropdown?.contains(e.target)) {
                notifDropdown?.classList.add('hidden');
            }
        });
    </script>

    <?php echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\pos_raul\resources\views/layouts/app.blade.php ENDPATH**/ ?>