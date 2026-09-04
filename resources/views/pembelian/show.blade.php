@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Detail Pembelian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Informasi lengkap transaksi pembelian</p>
        </div>
        <a href="{{ route('pembelian.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl transition">
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Info Header --}}
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $pembelian->tanggal->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pemasok</p>
                    <p class="font-medium text-gray-900 dark:text-white">
                        {{ $pembelian->pemasok->nama_distributor ?? '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Petugas</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $pembelian->user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Keterangan</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $pembelian->keterangan ?? '-' }}</p>
                </div>
                <div class="pt-3 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Pembelian</p>
                    <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                        Rp {{ number_format($pembelian->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Detail Barang --}}
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Barang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">#</th>
                                <th class="px-5 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Nama Produk</th>
                                <th class="px-5 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Qty</th>
                                <th class="px-5 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Harga Beli</th>
                                <th class="px-5 py-3 text-right font-semibold text-gray-600 dark:text-gray-300">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($pembelian->details as $index => $detail)
                                <tr>
                                    <td class="px-5 py-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $detail->produk->nama ?? '-' }}
                                    </td>
                                    <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-300">{{ $detail->qty }}</td>
                                    <td class="px-5 py-3 text-right text-gray-700 dark:text-gray-300">
                                        Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 