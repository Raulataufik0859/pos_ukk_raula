<?php $__env->startSection('content'); ?>
<div class="p-6">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Halaman Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola data produk, harga, dan stok barang</p>
        </div>
        <a href="<?php echo e(route('produk.create')); ?>"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Produk
        </a>
    </div>

    
    <form action="<?php echo e(route('produk.index')); ?>" method="GET" class="mb-6">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Cari nama atau kategori produk..."
                       class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
                Cari
            </button>
        </div>
    </form>

    
    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl text-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold w-12">#</th>
                        <th class="px-5 py-3.5 font-semibold w-16">Foto</th>
                        <th class="px-5 py-3.5 font-semibold min-w-[160px]">Nama Produk</th>
                        <th class="px-5 py-3.5 font-semibold min-w-[110px]">Kategori</th>
                        <th class="px-5 py-3.5 font-semibold text-right min-w-[130px]">Harga Beli</th>
                        <th class="px-5 py-3.5 font-semibold text-right min-w-[130px]">Harga Jual</th>
                        <th class="px-5 py-3.5 font-semibold text-center w-24">Stok</th>
                        <th class="px-5 py-3.5 font-semibold text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                <?php echo e($products->firstItem() + $index); ?>

                            </td>

                            <td class="px-5 py-4">
                                <?php if($product->foto): ?>
                                    <img src="<?php echo e(asset('storage/'.$product->foto)); ?>"
                                         class="w-10 h-10 object-cover rounded-lg border border-gray-200 dark:border-gray-600"
                                         alt="<?php echo e($product->nama); ?>">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                <?php echo e($product->nama); ?>

                            </td>

                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                <?php echo e($product->kategori->nama ?? '-'); ?>

                            </td>

<td class="px-5 py-4 text-right">
    <div class="inline-flex items-center justify-end gap-1 min-w-[120px] tabular-nums">
        <span class="text-gray-400 dark:text-gray-500 text-xs">Rp</span>
        <span class="text-gray-700 dark:text-gray-300 text-right w-[90px]">
            <?php echo e(number_format($product->harga_beli, 0, ',', '.')); ?>

        </span>
    </div>
</td>

<td class="px-5 py-4 text-right">
    <div class="inline-flex items-center justify-end gap-1 min-w-[120px] tabular-nums">
        <span class="text-gray-400 dark:text-gray-500 text-xs">Rp</span>
        <span class="text-gray-700 dark:text-gray-300 text-right w-[90px]">
            <?php echo e(number_format($product->harga_jual, 0, ',', '.')); ?>

        </span>
    </div>
</td>

                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium
                                    <?php echo e($product->stok <= 10
                                        ? 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                        : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'); ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($product->stok <= 10 ? 'bg-red-500' : 'bg-emerald-500'); ?>"></span>
                                    <?php echo e($product->stok); ?>

                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?php echo e(route('produk.edit', $product)); ?>"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                              bg-amber-50 text-amber-700 hover:bg-amber-100
                                              dark:bg-amber-900/30 dark:text-amber-300 transition">
                                        Edit
                                    </a>
                                    <form action="<?php echo e(route('produk.destroy', $product)); ?>" method="POST"
                                          onsubmit="return confirm('Yakin hapus produk ini?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                                       bg-red-50 text-red-700 hover:bg-red-100
                                                       dark:bg-red-900/30 dark:text-red-300 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data produk.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($products->hasPages()): ?>
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700
                        flex flex-col sm:flex-row items-center justify-between gap-3
                        bg-gray-50 dark:bg-gray-900/40">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan
                    <span class="font-medium text-gray-900 dark:text-white"><?php echo e($products->firstItem()); ?></span>
                    hingga
                    <span class="font-medium text-gray-900 dark:text-white"><?php echo e($products->lastItem()); ?></span>
                    dari
                    <span class="font-medium text-gray-900 dark:text-white"><?php echo e($products->total()); ?></span>
                    hasil
                </p>
                <div>
                    <?php echo e($products->links()); ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pos_raul\resources\views/produk/index.blade.php ENDPATH**/ ?>