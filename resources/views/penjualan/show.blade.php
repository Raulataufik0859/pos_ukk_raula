@extends('layouts.app')

@section('title', 'Nota Penjualan #' . $penjualan->id)
@section('header', 'Nota Penjualan')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-6">

    {{-- Tombol aksi (tidak ikut print) --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                Nota Transaksi #{{ $penjualan->id }}
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $penjualan->created_at->translatedFormat('l, d F Y • H:i') }} WIB
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Struk
            </button>
            <a href="{{ route('penjualan.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                Kembali
            </a>
            <a href="{{ route('penjualan.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition">
                Transaksi Baru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm no-print">
        {{ session('success') }}
    </div>
    @endif

    {{-- Area Nota / Struk --}}
    <div id="nota-area" class="max-w-md mx-auto bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-lg overflow-hidden">
        {{-- Header toko --}}
        <div class="text-center px-6 pt-6 pb-4 border-b border-dashed border-gray-200 dark:border-gray-700">
            <img src="{{ asset('imagelogo/lopos.jpg') }}" alt="Logo" class="w-14 h-14 rounded-xl object-cover mx-auto mb-2 shadow"
                 onerror="this.style.display='none'">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">POS Raula</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toko Raula • Point of Sale</p>
            <p class="text-xs text-gray-400 mt-1">Nota #{{ $penjualan->id }}</p>
        </div>

        {{-- Info --}}
        <div class="px-6 py-4 text-sm space-y-1.5 border-b border-dashed border-gray-200 dark:border-gray-700">
            <div class="flex justify-between">
                <span class="text-gray-500">Tanggal</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Kasir</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Metode</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->metode_pembayaran }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Status</span>
                <span class="font-medium {{ $penjualan->status === 'COMPLETED' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $penjualan->status }}
                </span>
            </div>
        </div>

        {{-- Item --}}
        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 uppercase">
                        <th class="pb-2">Item</th>
                        <th class="pb-2 text-center">Qty</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($penjualan->itemPenjualan as $item)
                    <tr>
                        <td class="py-2.5 pr-2">
                            <p class="font-medium text-gray-900 dark:text-white leading-tight">{{ $item->produk->nama ?? 'Produk dihapus' }}</p>
                            <p class="text-xs text-gray-400">@ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-2.5 text-center text-gray-700 dark:text-gray-300">{{ $item->kuantitas }}</td>
                        <td class="py-2.5 text-right font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-gray-400">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total --}}
        <div class="px-6 py-4 border-t border-dashed border-gray-200 dark:border-gray-700 space-y-1.5">
            <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white">
                <span>TOTAL</span>
                <span>Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
            </div>
            @if(isset($penjualan->uang_diterima) && $penjualan->uang_diterima)
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                <span>Uang diterima</span>
                <span>Rp {{ number_format($penjualan->uang_diterima, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                <span>Kembalian</span>
                <span>Rp {{ number_format(max(0, $penjualan->uang_diterima - $penjualan->total_pembayaran), 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        <div class="px-6 py-5 text-center text-xs text-gray-400 border-t border-dashed border-gray-200 dark:border-gray-700">
            Terima kasih telah berbelanja<br>
            <span class="font-medium text-gray-500">POS Raula &copy; {{ date('Y') }}</span>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden !important; }
    #nota-area, #nota-area * { visibility: visible !important; }
    #nota-area {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
    }
    .no-print { display: none !important; }
    #sidebar, header, nav, aside { display: none !important; }
}
</style>

@if(session('print'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () { window.print(); }, 400);
});
</script>
@endif
@endsection
