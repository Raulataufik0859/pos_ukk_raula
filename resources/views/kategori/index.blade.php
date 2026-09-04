@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kategori Produk</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola kategori / jenis produk</p>
        </div>
        <a href="{{ route('kategori.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-5 py-3.5 font-semibold w-14">#</th>
                        <th class="px-5 py-3.5 font-semibold">Nama Kategori</th>
                        <th class="px-5 py-3.5 font-semibold text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kategoris as $index => $kategori)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                            <td class="px-5 py-4 text-gray-500 dark:text-gray-400">
                                {{ method_exists($kategoris, 'firstItem') ? $kategoris->firstItem() + $index : $index + 1 }}
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $kategori->nama }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kategori.edit', $kategori) }}"
                                       class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium rounded-lg
                                              bg-amber-50 text-amber-700 hover:bg-amber-100
                                              dark:bg-amber-900/30 dark:text-amber-300 transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $kategori) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
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
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-gray-500 dark:text-gray-400">
                                Belum ada data kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination--}}
        @if(method_exists($kategoris, 'hasPages') && $kategoris->hasPages())
            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40">
                <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700
                        flex flex-col sm:flex-row items-center justify-between gap-3
                        bg-gray-50 dark:bg-gray-900/40">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Menampilkan
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->firstItem() }}</span>
                    hingga
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->lastItem() }}</span>
                    dari
                    <span class="font-medium text-gray-900 dark:text-white">{{ $kategoris->total() }}</span>
                    hasil
                </p>
                <div>
                {{ $kategoris->links() }}
            </div>
        @endif
    </div>

</div>
@endsection