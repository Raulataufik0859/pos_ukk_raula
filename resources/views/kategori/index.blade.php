@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('header', 'Kategori Produk')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Kategori Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola kategori produk toko</p>
        </div>
        <a href="{{ route('kategori.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm border border-emerald-200 dark:border-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl text-sm border border-red-200 dark:border-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/80 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold w-14">#</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Kategori</th>
                        <th class="px-5 py-3.5 font-semibold text-center">Jumlah Produk</th>
                        <th class="px-5 py-3.5 font-semibold">Dibuat Oleh</th>
                        <th class="px-5 py-3.5 font-semibold">Tanggal Dibuat</th>
                        <th class="px-5 py-3.5 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($kategoris as $index => $kategori)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                {{ $kategoris->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $kategori->nama }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ ($kategori->produks_count ?? 0) > 0
                                        ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300'
                                        : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400' }}">
                                    {{ $kategori->produks_count ?? 0 }} produk
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-700 dark:text-gray-300">
                                {{ $kategori->user->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                {{ $kategori->created_at?->translatedFormat('d M Y') ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kategori.edit', $kategori) }}"
                                       class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-blue-600 text-white hover:bg-blue-500 transition shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="return_query" value="{{ http_build_query(request()->query()) }}">
                                        <button type="submit"
                                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg bg-rose-600 text-white hover:bg-rose-500 transition shadow-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kategoris->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-3 bg-gray-50 dark:bg-gray-900/40">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->firstItem() }}</span>
                    –
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->lastItem() }}</span>
                    dari
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->total() }}</span>
                </p>
                <div>{{ $kategoris->links() }}</div>
            </div>
        @endif
    </div>
</div>
@endsection
