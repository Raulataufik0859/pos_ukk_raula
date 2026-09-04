@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Stok</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Posisi stok produk saat ini</p>
        </div>
        <a href="{{ route('laporan.index') }}"
           class="px-4 py-2.5 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition">
            Kembali
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold w-12">#</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Produk</th>
                        <th class="px-5 py-3.5 font-semibold">Kategori</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Stok</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($produks as $index => $produk)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $produk->nama }}
                            </td>
                            <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                {{ $produk->kategori->nama ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-center font-medium text-gray-900 dark:text-white">
                                {{ $produk->stok }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($produk->stok <= 0)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300">Habis</span>
                                @elseif($produk->stok <= 10)
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">Menipis</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data produk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection