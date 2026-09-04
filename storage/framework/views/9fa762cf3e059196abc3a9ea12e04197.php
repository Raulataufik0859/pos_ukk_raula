<?php $__env->startSection('content'); ?>
<div class="p-3 lg:p-4 h-[calc(100vh-72px)] flex flex-col overflow-hidden">

    <?php if(session('success')): ?>
        <div class="mb-2 p-3 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl text-sm shrink-0"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-2 p-3 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl text-sm shrink-0"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-5 gap-3 min-h-0">

        
        <div class="lg:col-span-3 flex flex-col min-h-0 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            
            <div class="p-3 border-b border-gray-200 dark:border-gray-700 space-y-3 shrink-0">
                <form method="GET" action="<?php echo e(route('penjualan.create')); ?>" class="relative" id="search-form">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" id="search-input"
                           placeholder="Cari menu..."
                           class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none"
                           autofocus>
                    <?php if(request('kategori')): ?>
                        <input type="hidden" name="kategori" value="<?php echo e(request('kategori')); ?>">
                    <?php endif; ?>
                </form>

                
                <div class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    <a href="<?php echo e(route('penjualan.create', array_filter(['search' => request('search')]))); ?>"
                       class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-medium transition
                       <?php echo e(!request('kategori') ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                        Semua
                    </a>
                    <?php $__currentLoopData = ($kategoris ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('penjualan.create', array_filter(['kategori' => $kat->id, 'search' => request('search')]))); ?>"
                           class="shrink-0 px-3.5 py-1.5 rounded-full text-xs font-medium transition
                           <?php echo e(request('kategori') == $kat->id ? 'bg-indigo-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'); ?>">
                            <?php echo e($kat->nama); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="flex-1 overflow-y-auto p-3">
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <form method="POST" action="<?php echo e(route('itempenjualan.store')); ?>" class="group">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit"
                                    class="w-full text-left bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700
                                           hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md transition overflow-hidden
                                           disabled:opacity-50 disabled:cursor-not-allowed"
                                    <?php echo e($sale->status === 'COMPLETED' || $product->stok <= 0 ? 'disabled' : ''); ?>>

                                
                                <div class="aspect-square bg-gray-100 dark:bg-gray-800 relative overflow-hidden">
                                    <?php if($product->foto): ?>
                                        <img src="<?php echo e(asset('storage/'.$product->foto)); ?>" alt="<?php echo e($product->nama); ?>"
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-semibold
                                          <?php echo e($product->stok <= 5 ? 'bg-rose-500 text-white' : 'bg-black/50 text-white'); ?>">
                                        Stok <?php echo e($product->stok); ?>

                                    </span>
                                </div>

                                
                                <div class="p-2.5">
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide truncate">
                                        <?php echo e($product->kategori->nama ?? 'Umum'); ?>

                                    </p>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate mt-0.5 leading-tight">
                                        <?php echo e($product->nama); ?>

                                    </p>
                                    <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mt-1">
                                        Rp <?php echo e(number_format($product->harga_jual, 0, ',', '.')); ?>

                                    </p>
                                </div>
                            </button>
                        </form>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full text-center py-16 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-sm">Tidak ada produk ditemukan</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2 flex flex-col min-h-0 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">

            
            <div id="cart-view" class="flex flex-col h-full min-h-0">
                <div class="px-4 py-3.5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between shrink-0">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Pesanan</h3>
                        <p class="text-[11px] text-gray-400">Transaksi #<?php echo e($sale->id); ?></p>
                    </div>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 font-medium">
                        <?php echo e($sale->itemPenjualan->count()); ?> item
                    </span>
                </div>

                <div class="flex-1 overflow-y-auto p-3 space-y-2 min-h-0">
                    <?php $__empty_1 = true; $__currentLoopData = $sale->itemPenjualan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-700 overflow-hidden shrink-0">
                                <?php if($item->produk?->foto): ?>
                                    <img src="<?php echo e(asset('storage/'.$item->produk->foto)); ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white text-sm truncate">
                                    <?php echo e($item->produk?->nama ?? 'Produk tidak ditemukan'); ?>

                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?>

                                </p>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <form method="POST" action="<?php echo e(route('itempenjualan.update', $item->id)); ?>" class="flex items-center gap-1">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PUT'); ?>
                                        <button type="button" onclick="this.parentElement.querySelector('input').stepDown(); this.parentElement.submit();"
                                                class="w-6 h-6 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold hover:bg-gray-300"
                                                <?php echo e($sale->status === 'COMPLETED' ? 'disabled' : ''); ?>>−</button>
                                        <input type="number" name="quantity" value="<?php echo e($item->kuantitas); ?>" min="1"
                                               class="w-10 px-1 py-0.5 text-center text-xs rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                                               onchange="this.form.submit()"
                                               <?php echo e($sale->status === 'COMPLETED' ? 'readonly' : ''); ?>>
                                        <button type="button" onclick="this.parentElement.querySelector('input').stepUp(); this.parentElement.submit();"
                                                class="w-6 h-6 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold hover:bg-gray-300"
                                                <?php echo e($sale->status === 'COMPLETED' ? 'disabled' : ''); ?>>+</button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('itempenjualan.destroy', $item->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-md"
                                                <?php echo e($sale->status === 'COMPLETED' ? 'disabled' : ''); ?>>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                    Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-16 text-gray-400 text-sm">
                            <svg class="w-10 h-10 mx-auto mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Keranjang masih kosong<br>
                            <span class="text-xs">Klik produk untuk menambah</span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 p-4 space-y-3 shrink-0">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500 dark:text-gray-400 text-sm">Subtotal</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                            Rp <?php echo e(number_format($sale->total_pembayaran, 0, ',', '.')); ?>

                        </span>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Metode Pembayaran</label>
                        <div class="grid grid-cols-3 gap-1.5">
                            <button type="button" onclick="goToPayment('CASH')"
                                    class="py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition"
                                    <?php echo e($sale->itemPenjualan->count() === 0 ? 'disabled' : ''); ?>>Cash</button>
                            <button type="button" onclick="goToPayment('QRIS')"
                                    class="py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition"
                                    <?php echo e($sale->itemPenjualan->count() === 0 ? 'disabled' : ''); ?>>QRIS</button>
                            <button type="button" onclick="goToPayment('TRANSFER')"
                                    class="py-2.5 text-xs font-medium rounded-xl border border-gray-200 dark:border-gray-600 hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition"
                                    <?php echo e($sale->itemPenjualan->count() === 0 ? 'disabled' : ''); ?>>Transfer</button>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('penjualan.destroy', $sale->id)); ?>"
                          onsubmit="return confirm('Yakin ingin membatalkan transaksi ini?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                                class="w-full py-2 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl text-sm font-medium transition"
                                <?php echo e($sale->status === 'COMPLETED' ? 'disabled' : ''); ?>>
                            Batalkan Transaksi
                        </button>
                    </form>
                </div>
            </div>

            
            <div id="payment-view" class="hidden flex flex-col h-full min-h-0">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between shrink-0">
                    <button type="button" onclick="backToCart()"
                            class="flex items-center gap-1.5 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Kembali
                    </button>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400" id="payment-method-label">-</span>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <div class="max-w-sm mx-auto space-y-4">
                        <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 max-h-36 overflow-y-auto">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-wide">
                                <?php echo e($sale->itemPenjualan->count()); ?> Item
                            </p>
                            <div class="space-y-1.5">
                                <?php $__currentLoopData = $sale->itemPenjualan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex justify-between text-xs gap-2">
                                        <span class="text-gray-700 dark:text-gray-300 truncate">
                                            <?php echo e($item->produk?->nama ?? '-'); ?> <span class="text-gray-400">×<?php echo e($item->kuantitas); ?></span>
                                        </span>
                                        <span class="text-gray-900 dark:text-white whitespace-nowrap font-medium">
                                            Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="text-center py-1">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Total yang harus dibayar</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-0.5">
                                Rp <?php echo e(number_format($sale->total_pembayaran, 0, ',', '.')); ?>

                            </p>
                        </div>

                        <div id="payment-qris" class="hidden">
                            <div class="p-4 pb-5 text-center">
                                <div class="inline-block bg-white p-2 rounded-xl cursor-pointer" onclick="openQrisModal()" title="Klik untuk perbesar">
                                    <img src="<?php echo e(asset('imageqris/qris-allpay.jpg')); ?>" alt="QRIS"
                                         class="w-52 h-52 object-contain mx-auto" onerror="this.src='<?php echo e(asset('imageqris/qris')); ?>'">
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Scan QR untuk membayar</p>
                            </div>
                        </div>

                        <div id="payment-transfer" class="hidden space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pilih Bank Transfer</label>
                                <select id="bank-transfer"
                                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none"
                                        onchange="tampilkanRekening()">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="Mandiri">Mandiri</option>
                                </select>
                            </div>
                            <div id="info-rekening" class="hidden p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Transfer ke rekening:</p>
                                <p class="font-bold text-gray-900 dark:text-white text-lg" id="nomor-rekening">-</p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">a.n. <span id="nama-rekening" class="font-medium">Toko Raula</span></p>
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2" id="bank-label">-</p>
                            </div>
                        </div>

                        <div id="payment-cash" class="hidden space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Uang Diterima <span class="text-red-500">*</span></label>
                                <input type="number" id="uang-diterima" min="0" step="1000" placeholder="Contoh: 200000"
                                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none"
                                       oninput="hitungKembalian()">
                            </div>
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-500 dark:text-gray-400">Total Belanja</span>
                                    <span class="font-medium text-gray-900 dark:text-white">Rp <?php echo e(number_format($sale->total_pembayaran, 0, ',', '.')); ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Kembalian</span>
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400" id="label-kembalian">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 dark:border-gray-700 shrink-0">
                    <form method="POST" action="<?php echo e(route('penjualan.update', $sale->id)); ?>" id="final-checkout-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="metode_pembayaran" id="final-metode" value="">
                        <input type="hidden" name="bank" id="final-bank" value="">
                        <input type="hidden" name="uang_diterima" id="final-uang" value="">
                        <input type="hidden" name="status" value="COMPLETED">
                        <button type="submit" id="btn-konfirmasi" disabled
                                class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-semibold rounded-xl transition shadow-sm">
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const TOTAL_BELANJA = <?php echo e($sale->total_pembayaran); ?>;
const rekeningBank = {
    BCA: { nomor: '1234567890', nama: 'Toko Raula' },
    BNI: { nomor: '0987654321', nama: 'Toko Raula' },
    BRI: { nomor: '1122334455', nama: 'Toko Raula' },
    Mandiri: { nomor: '5566778899', nama: 'Toko Raula' }
};

function tampilkanRekening() {
    const bank = document.getElementById('bank-transfer').value;
    const info = document.getElementById('info-rekening');
    const btn = document.getElementById('btn-konfirmasi');
    if (!bank) { info.classList.add('hidden'); if (btn) btn.disabled = true; return; }
    document.getElementById('nomor-rekening').textContent = rekeningBank[bank].nomor;
    document.getElementById('nama-rekening').textContent = rekeningBank[bank].nama;
    document.getElementById('bank-label').textContent = 'Bank ' + bank;
    info.classList.remove('hidden');
    if (btn) btn.disabled = false;
}

function hitungKembalian() {
    const diterima = parseFloat(document.getElementById('uang-diterima').value) || 0;
    const kembalian = diterima - TOTAL_BELANJA;
    const label = document.getElementById('label-kembalian');
    const btn = document.getElementById('btn-konfirmasi');
    if (diterima <= 0) {
        label.textContent = 'Rp 0';
        label.className = 'font-bold text-emerald-600 dark:text-emerald-400';
        if (btn) btn.disabled = true; return;
    }
    if (kembalian < 0) {
        label.textContent = 'Kurang Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(kembalian));
        label.className = 'font-bold text-red-500';
        if (btn) btn.disabled = true;
    } else {
        label.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(kembalian);
        label.className = 'font-bold text-emerald-600 dark:text-emerald-400';
        if (btn) btn.disabled = false;
    }
}

function goToPayment(method) {
    document.getElementById('cart-view').classList.add('hidden');
    document.getElementById('payment-view').classList.remove('hidden');
    document.getElementById('payment-qris').classList.add('hidden');
    document.getElementById('payment-transfer').classList.add('hidden');
    document.getElementById('payment-cash').classList.add('hidden');
    document.getElementById('final-metode').value = method;
    document.getElementById('payment-method-label').textContent = method;
    const btn = document.getElementById('btn-konfirmasi');
    if (method === 'QRIS') {
        document.getElementById('payment-qris').classList.remove('hidden');
        if (btn) btn.disabled = false;
    } else if (method === 'TRANSFER') {
        document.getElementById('payment-transfer').classList.remove('hidden');
        document.getElementById('bank-transfer').value = '';
        document.getElementById('info-rekening').classList.add('hidden');
        if (btn) btn.disabled = true;
    } else {
        document.getElementById('payment-cash').classList.remove('hidden');
        document.getElementById('uang-diterima').value = '';
        hitungKembalian();
    }
}

function backToCart() {
    document.getElementById('payment-view').classList.add('hidden');
    document.getElementById('cart-view').classList.remove('hidden');
}

function openQrisModal() {
    const img = document.querySelector('#payment-qris img');
    if (img) window.open(img.src, '_blank');
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('final-checkout-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const metode = document.getElementById('final-metode').value;
            const bank = document.getElementById('bank-transfer')?.value || '';
            const uang = document.getElementById('uang-diterima')?.value || '';
            if (metode === 'TRANSFER' && !bank) {
                e.preventDefault(); alert('Silakan pilih bank transfer terlebih dahulu.'); return false;
            }
            if (metode === 'CASH') {
                const diterima = parseFloat(uang) || 0;
                if (diterima < TOTAL_BELANJA) {
                    e.preventDefault(); alert('Uang diterima kurang dari total belanja.'); return false;
                }
                document.getElementById('final-uang').value = diterima;
            }
            document.getElementById('final-bank').value = bank;
        });
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pos_raul\resources\views/penjualan/form.blade.php ENDPATH**/ ?>