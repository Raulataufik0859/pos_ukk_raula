@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laporan Penjualan</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Rekap penjualan berdasarkan periode</p>
            </div>
            <a href="{{ route('laporan.index') }}"
                class="px-4 py-2.5 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition">
                Kembali
            </a>
        </div>

        <form action="{{ route('laporan.penjualan') }}" method="GET" class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal
                            Awal</label>
                        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal
                            Akhir</label>
                        <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition">
                        Tampilkan
                    </button>
                </div>
            </div>
        </form>

        <div
            class="mb-6 p-5 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800">
            <p class="text-sm text-indigo-600 dark:text-indigo-400">Total Penjualan Periode Ini</p>
            <p class="text-2xl font-bold text-indigo-700 dark:text-indigo-300 mt-1">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-3.5 font-semibold w-12">#</th>
                            <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                            <th class="px-5 py-3.5 font-semibold">No. Transaksi</th>
                            <th class="px-5 py-3.5 font-semibold">Petugas</th>
                            <th class="px-5 py-3.5 font-semibold text-center">Metode</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($penjualans as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                                <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                                <td class="px-5 py-4 text-gray-900 dark:text-white whitespace-nowrap">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                    #{{ $item->id }}
                                </td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $item->user->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @php
                                        $metode = strtoupper($item->metode_pembayaran ?? '-');
                                        $metodeClass = match ($metode) {
                                            'CASH'
                                                => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                            'QRIS' => 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                            'TRANSFER'
                                                => 'bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
                                            default => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300',
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $metodeClass }}">
                                        {{ $metode }}
                                    </span>
                                </td>
                                <td
                                    class="px-5 py-4 text-right font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                    Rp {{ number_format($item->total_pembayaran ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data penjualan pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
