@extends('layouts.app')

@section('content')
    <div class="p-6">

        {{-- Header --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ringkasan performa toko hari ini</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        @can('viewAny', App\Models\User::class)
            {{-- Stat Cards Penjualan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
                {{-- Total Penjualan --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Penjualan</p>
                            <p class="text-xs text-gray-400 mt-0.5">Hari ini</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                                Rp {{ number_format($totalPenjualanHariIni ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Jumlah Transaksi --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
                            <p class="text-xs text-gray-400 mt-0.5">Hari ini</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                                {{ $jumlahTransaksiHariIni ?? 0 }}
                            </p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Tunai --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pembayaran Tunai</p>
                            <p class="text-xs text-gray-400 mt-0.5">Hari ini</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                                Rp {{ number_format($totalTunaiHariIni ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Non-Tunai --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Non-Tunai</p>
                            <p class="text-xs text-gray-400 mt-0.5">QRIS / Transfer</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                                Rp {{ number_format($totalNonTunaiHariIni ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
                            <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        {{-- Inventory --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">

            {{-- Stok Rendah --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Stok Menipis</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-semibold w-12">#</th>
                                <th class="px-5 py-3 font-semibold">Produk</th>
                                <th class="px-5 py-3 font-semibold text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($produkStokRendah as $index => $produk)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-5 py-3 text-gray-500">
                                        {{ method_exists($produkStokRendah, 'firstItem') ? $produkStokRendah->firstItem() + $index : $index + 1 }}
                                    </td>
                                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $produk->nama }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                            {{ $produk->stok }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-5 py-10 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        Semua stok aman
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (method_exists($produkStokRendah, 'hasPages') && $produkStokRendah->hasPages())
                    <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $produkStokRendah->links() }}
                    </div>
                @endif
            </div>

            {{-- Stok Habis --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Stok Habis</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-semibold w-12">#</th>
                                <th class="px-5 py-3 font-semibold">Produk</th>
                                <th class="px-5 py-3 font-semibold text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($produkStokHabis as $index => $produk)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                    <td class="px-5 py-3 text-gray-500">
                                        {{ method_exists($produkStokHabis, 'firstItem') ? $produkStokHabis->firstItem() + $index : $index + 1 }}
                                    </td>
                                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $produk->nama }}
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                            {{ $produk->stok }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-5 py-10 text-center text-gray-500 dark:text-gray-400 text-sm">
                                        Tidak ada produk habis stok
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (method_exists($produkStokHabis, 'hasPages') && $produkStokHabis->hasPages())
                    <div class="px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        {{ $produkStokHabis->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Best Seller --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white text-sm">Produk Terlaris</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-3 font-semibold w-16">#</th>
                            <th class="px-5 py-3 font-semibold">Nama Produk</th>
                            <th class="px-5 py-3 font-semibold text-center">Stok</th>
                            <th class="px-5 py-3 font-semibold text-right">Unit Terjual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($produkTerlaris as $index => $produk)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                <td class="px-5 py-3">
                                    @if ($index === 0)
                                        <span
                                            class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 text-xs font-bold">1</span>
                                    @elseif($index === 1)
                                        <span
                                            class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 text-xs font-bold">2</span>
                                    @elseif($index === 2)
                                        <span
                                            class="inline-flex w-7 h-7 items-center justify-center rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300 text-xs font-bold">3</span>
                                    @else
                                        <span class="text-gray-500 pl-2">{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-white">{{ $produk->nama }}</td>
                                <td class="px-5 py-3 text-center text-gray-600 dark:text-gray-300">{{ $produk->stok }}
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($produk->total_terjual ?? 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data produk terlaris.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
