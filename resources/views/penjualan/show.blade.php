@extends('layouts.app')

@section('title', match($penjualan->status) {
    'OPEN' => 'Detail Transaksi (Belum Selesai)',
    'PENDING' => 'Menunggu Verifikasi Transfer',
    default => 'Nota Penjualan #'.$penjualan->id
})
@section('header', 'Penjualan')

@section('content')
@php
    $isOpen = $penjualan->status === 'OPEN';
    $isPending = $penjualan->status === 'PENDING';
    $isCompleted = $penjualan->status === 'COMPLETED';
    $isAdmin = strtolower(auth()->user()->role->name ?? '') === 'admin';
@endphp

<div class="w-full px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 no-print">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                @if($isOpen)
                    Detail Transaksi (Belum Selesai)
                @elseif($isPending)
                    Menunggu Verifikasi Transfer #{{ $penjualan->id }}
                @else
                    Nota Transaksi #{{ $penjualan->id }}
                @endif
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $penjualan->created_at->translatedFormat('l, d F Y') }} •
                <span class="nota-clock font-medium text-indigo-600 dark:text-indigo-400">{{ $penjualan->created_at->format('H:i:s') }}</span> WIB
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($isOpen)
                <a href="{{ route('penjualan.edit', $penjualan) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-sm transition">
                    Lanjutkan Pembayaran
                </a>
                <form method="POST" action="{{ route('penjualan.destroy', $penjualan) }}"
                      onsubmit="return confirm('Batalkan transaksi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-rose-600 hover:bg-rose-500 rounded-xl shadow-sm transition">
                        Batal
                    </button>
                </form>
            @elseif($isPending)
                @if($isAdmin)
                <form method="POST" action="{{ route('penjualan.confirm-transfer', $penjualan) }}"
                      onsubmit="return confirm('Konfirmasi dana transfer sudah masuk ke rekening toko?')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl shadow-sm transition">
                        Konfirmasi Transfer Masuk
                    </button>
                </form>
                <form method="POST" action="{{ route('penjualan.reject-transfer', $penjualan) }}"
                      onsubmit="return confirm('Tolak transfer ini? Transaksi akan dibatalkan. Stok belum berkurang.')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-rose-600 hover:bg-rose-500 rounded-xl shadow-sm transition">
                        Tolak / Batalkan Transfer
                    </button>
                </form>
                @endif
                <span class="inline-flex items-center px-3 py-2 text-xs font-semibold rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                    PENDING — menunggu verifikasi admin
                </span>
            @else
                <button type="button" onclick="window.print()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-sm transition">
                    Cetak Struk
                </button>
                <a href="{{ route('penjualan.create', ['baru' => 1]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-500 rounded-xl transition">
                    Transaksi Baru
                </a>
            @endif
            <a href="{{ route('penjualan.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                ← Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-sm no-print border border-emerald-200 dark:border-emerald-800">
        {{ session('success') }}
    </div>
    @endif

    @if($isPending)
    <div class="mb-4 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-800 dark:text-amber-200 text-sm no-print border border-amber-200 dark:border-amber-800">
        <strong>Info:</strong> Pembayaran transfer tidak langsung lunas. Status <strong>PENDING</strong> sampai admin mengonfirmasi dana masuk.
        Stok produk belum dikurangi selama status masih PENDING.
    </div>
    @endif

    <div id="nota-area" class="max-w-md mx-auto bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-lg overflow-hidden">
        <div class="text-center px-6 pt-6 pb-4 border-b border-dashed border-gray-200 dark:border-gray-700">
            <img src="{{ asset('imagelogo/lopos.jpg') }}" alt="Logo" class="w-14 h-14 rounded-xl object-contain bg-white p-0.5 mx-auto mb-2 shadow"
                 onerror="this.style.display='none'">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">POS Raula</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Toko Raula • Point of Sale</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Nota #{{ $penjualan->id }}</p>
        </div>

        <div class="px-6 py-4 text-sm space-y-1.5 border-b border-dashed border-gray-200 dark:border-gray-700">
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Tanggal</span>
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ $penjualan->created_at->format('d/m/Y') }}
                    <span class="nota-clock">{{ $penjualan->created_at->format('H:i:s') }}</span>
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Kasir</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Metode</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->metode_pembayaran }}</span>
            </div>
            @if(strtoupper($penjualan->metode_pembayaran) === 'TRANSFER')
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Bank Tujuan</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->bank_transfer ?: '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Nama Pengirim</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $penjualan->nama_pengirim ?: '-' }}</span>
            </div>
            @endif
            <div class="flex justify-between">
                <span class="text-gray-500 dark:text-gray-400">Status</span>
                <span class="font-semibold
                    {{ $isCompleted ? 'text-emerald-600 dark:text-emerald-400' : ($isPending ? 'text-amber-600 dark:text-amber-400' : 'text-gray-600 dark:text-gray-300') }}">
                    {{ $penjualan->status }}
                </span>
            </div>
        </div>

        <div class="px-6 py-4">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-gray-400 dark:text-gray-500 uppercase">
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
                            <p class="text-xs text-gray-400 dark:text-gray-500">@ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                        </td>
                        <td class="py-2.5 text-center text-gray-700 dark:text-gray-300">{{ $item->kuantitas }}</td>
                        <td class="py-2.5 text-right font-medium text-gray-900 dark:text-white">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-6 text-center text-gray-400 dark:text-gray-500">Tidak ada item</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-dashed border-gray-200 dark:border-gray-700">
            <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white">
                <span>TOTAL</span>
                <span>Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="px-6 py-5 text-center text-xs text-gray-400 dark:text-gray-500 border-t border-dashed border-gray-200 dark:border-gray-700">
            @if($isCompleted)
                Terima kasih telah berbelanja
            @elseif($isPending)
                Transaksi akan selesai setelah pembayaran diverifikasi
            @else
                Transaksi belum diselesaikan
            @endif
            <br>
            <span class="font-medium text-gray-500 dark:text-gray-400">POS Raula &copy; {{ date('Y') }}</span>
            <p class="mt-1">Waktu: <span class="nota-clock">--:--:--</span> WIB</p>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden !important; }
    #nota-area, #nota-area * { visibility: visible !important; }
    #nota-area {
        position: absolute !important; left: 0 !important; top: 0 !important;
        width: 100% !important; max-width: 100% !important; margin: 0 !important;
        border: none !important; box-shadow: none !important; border-radius: 0 !important;
    }
    .no-print { display: none !important; }
}
</style>

@if(session('print') && $isCompleted)
<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () { window.print(); }, 400);
});
</script>
@endif
<script>
function updateNotaClock() {
    const now = new Date();
    const s = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    document.querySelectorAll('.nota-clock').forEach(el => el.textContent = s);
}
updateNotaClock();
setInterval(updateNotaClock, 1000);
</script>
@endsection
