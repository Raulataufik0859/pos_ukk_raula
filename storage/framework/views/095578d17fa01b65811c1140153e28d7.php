<?php
    $user = auth()->user();
    $roleName = strtolower($user->role->name ?? '');
    $isAdmin = $roleName === 'admin';
    $isKasir = $roleName === 'kasir';
    $avatarUrl = $user->avatar ? asset('storage/'.$user->avatar) : null;
?>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 
           transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out flex flex-col">

    
    <a href="<?php echo e(route('profile.show')); ?>"
        class="h-[72px] flex items-center px-4 border-b border-gray-200 dark:border-gray-800 shrink-0
               hover:bg-gray-50 dark:hover:bg-gray-800/60 transition group"
        title="Lihat & Edit Profil">
        <div class="flex items-center gap-3 w-full">
            <div class="relative shrink-0">
                <?php if($avatarUrl): ?>
                    <img src="<?php echo e($avatarUrl); ?>" alt="Avatar"
                         class="w-10 h-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-indigo-300 dark:group-hover:ring-indigo-500 transition">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm
                                ring-2 ring-transparent group-hover:ring-indigo-300 dark:group-hover:ring-indigo-500 transition">
                        <?php echo e(strtoupper(substr($user->name ?? 'U', 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-900 rounded-full"></span>
            </div>
            <div class="sidebar-text flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                    <?php echo e($user->name ?? 'User'); ?>

                </p>
                <p class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                    
                    <?php echo e(ucfirst($roleName)); ?>

                </p>
            </div>
            <svg class="sidebar-text w-4 h-4 text-gray-400 group-hover:text-indigo-500 shrink-0 transition"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </div>
    </a>

    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">

        
        <div class="space-y-0.5">
            <a href="<?php echo e(url('/dashboard')); ?>"
                class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                <?php echo e(request()->is('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="sidebar-text">Dashboard</span>
            </a>
        </div>

        
        <div>
            <button type="button" class="group-toggle sidebar-text w-full flex items-center justify-between px-3 mb-1.5 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hover:text-gray-600 dark:hover:text-gray-300 transition"
                    data-target="menu-master">
                <span>Master Data</span>
                <svg class="group-chevron w-3.5 h-3.5 transition-transform duration-200 <?php echo e(request()->is('produk*') || request()->is('kategori*') || request()->is('pemasok*') ? 'rotate-180' : ''); ?>"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="menu-master" class="space-y-0.5 <?php echo e(request()->is('produk*') || request()->is('kategori*') || request()->is('pemasok*') ? '' : ''); ?>">
                <?php if($isAdmin): ?>
                <a href="<?php echo e(route('kategori.index')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('kategori*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="sidebar-text">Kategori</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(url('/produk')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('produk*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="sidebar-text">Produk</span>
                </a>

                <?php if($isAdmin): ?>
                <a href="<?php echo e(route('pemasok.index')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('pemasok*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="sidebar-text">Pemasok</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div>
            <button type="button" class="group-toggle sidebar-text w-full flex items-center justify-between px-3 mb-1.5 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hover:text-gray-600 dark:hover:text-gray-300 transition"
                    data-target="menu-transaksi">
                <span>Transaksi</span>
                <svg class="group-chevron w-3.5 h-3.5 transition-transform duration-200 <?php echo e(request()->is('pembelian*') || request()->is('penjualan*') ? 'rotate-180' : ''); ?>"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="menu-transaksi" class="space-y-0.5">
                <?php if($isAdmin): ?>
                <a href="<?php echo e(route('pembelian.index')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('pembelian*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="sidebar-text">Pembelian</span>
                </a>
                <?php endif; ?>

                <a href="<?php echo e(url('/penjualan')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('penjualan*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="sidebar-text">Penjualan</span>
                </a>
            </div>
        </div>

        
        <?php if($isAdmin): ?>
        <div>
            <button type="button" class="group-toggle sidebar-text w-full flex items-center justify-between px-3 mb-1.5 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider hover:text-gray-600 dark:hover:text-gray-300 transition"
                    data-target="menu-report">
                <span>Report</span>
                <svg class="group-chevron w-3.5 h-3.5 transition-transform duration-200 <?php echo e(request()->is('laporan*') ? 'rotate-180' : ''); ?>"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div id="menu-report" class="space-y-0.5 <?php echo e(request()->is('laporan*') ? '' : 'hidden'); ?>">
                <a href="<?php echo e(route('laporan.index')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('laporan') && !request()->is('laporan/*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sidebar-text">Ringkasan</span>
                </a>
                <a href="<?php echo e(route('laporan.penjualan')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('laporan/penjualan*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="sidebar-text">Penjualan</span>
                </a>
                <a href="<?php echo e(route('laporan.pembelian')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('laporan/pembelian*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="sidebar-text">Pembelian</span>
                </a>
                <a href="<?php echo e(route('laporan.stok')); ?>"
                    class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                    <?php echo e(request()->is('laporan/stok*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="sidebar-text">Stok</span>
                </a>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($isAdmin): ?>
        <div>
            <p class="sidebar-text px-3 mb-1.5 text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">People</p>
            <a href="<?php echo e(url('/admin/users')); ?>"
                class="sidebar-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition
                <?php echo e(request()->is('admin/users*') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'); ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="sidebar-text">Pengguna</span>
            </a>
        </div>
        <?php endif; ?>

    </nav>

    <div class="p-3 border-t border-gray-200 dark:border-gray-800 shrink-0">
        <p class="sidebar-text text-[11px] text-center text-gray-400 dark:text-gray-500">
            POS Raula &copy; <?php echo e(date('Y')); ?>

        </p>
    </div>
</aside>

<script>
document.querySelectorAll('.group-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const target = document.getElementById(btn.dataset.target);
        const chevron = btn.querySelector('.group-chevron');
        if (!target) return;
        target.classList.toggle('hidden');
        chevron?.classList.toggle('rotate-180');
    });
});
</script>
<?php /**PATH C:\laragon\www\pos_raul\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>