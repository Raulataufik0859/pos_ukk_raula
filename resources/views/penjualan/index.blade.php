@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Halaman Penjualan</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Daftar transaksi penjualan</p>
        </div>
        <a href="{{ route('penjualan.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Transaksi
        </a>
    </div>

    {{-- Search --}}
    <form action="{{ route('penjualan.index') }}" method="GET" class="mb-6">
        <div class="flex gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari transaksi penjualan..."
                       class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
                Cari
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold w-12">#</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                        <th class="px-5 py-3.5 font-semibold">Kasir</th>
                        <th class="px-5 py-3.5 font-semibold text-right">Total</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Metode</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-center w-48">Aksi</th>
                    </tr>
                </thead>
               <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @forelse($sales as $index => $penjualan)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                {{ $sales->firstItem() + $index }}
            </td>
            <td class="px-5 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                {{ $penjualan->created_at->format('d-m-Y H:i') }}
            </td>
            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                {{ $penjualan->user->name ?? '-' }}
            </td>

<td class="px-5 py-4 text-right">
    <div class="inline-flex items-center justify-end gap-1 min-w-[120px] tabular-nums">
        <span class="text-gray-400 dark:text-gray-500 text-xs">Rp</span>
        <span class="text-gray-700 dark:text-gray-300 text-right w-[90px]">
            {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}
        </span>
    </div>
</td>

            <td class="px-5 py-4 text-center">
                @php
                    $metode = strtoupper($penjualan->metode_pembayaran ?? '-');
                    $metodeClass = match($metode) {
                        'CASH' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                        'QRIS' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                        'TRANSFER' => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                        default => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                    };
                @endphp
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $metodeClass }}">
                    {{ $metode }}
                </span>
            </td>
            <td class="px-5 py-4 text-center">
                @php
                    $status = strtoupper($penjualan->status ?? 'OPEN');
                    $statusClass = $status === 'COMPLETED'
                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300'
                        : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300';
                @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $status === 'COMPLETED' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $status }}
                </span>
            </td>
            <td class="px-5 py-4">
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('penjualan.show', $penjualan) }}"
                       class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 transition">
                        Detail
                    </a>

                    @if($status !== 'COMPLETED')
                        <a href="{{ route('penjualan.edit', $penjualan) }}"
                           class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-300 transition">
                            Lanjutkan
                        </a>
                        <form action="{{ route('penjualan.destroy', $penjualan) }}" method="POST"
                              onsubmit="return confirm('Yakin hapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-2.5 py-1.5 text-xs font-medium rounded-lg bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 transition">
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-5 py-16 text-center text-gray-500 dark:text-gray-400">
                Belum ada data penjualan.
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>

        {{-- Paginationnzz --}}
        @if($sales->hasPages())
            <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700
                        flex flex-col sm:flex-row items-center justify-between gap-3
                        bg-gray-50 dark:bg-gray-900/40">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan
                    <span class="font-medium text-gray-900 dark:text-white">{{ $sales->firstItem() }}</span>
                    hingga
                    <span class="font-medium text-gray-900 dark:text-white">{{ $sales->lastItem() }}</span>
                    dari
                    <span class="font-medium text-gray-900 dark:text-white">{{ $sales->total() }}</span>
                    hasil
                </p>
                <div>
                    {{ $sales->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection