@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Pembelian</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat pembelian barang dari pemasok</p>
        </div>
        <a href="{{ route('pembelian.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
            ← Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-xl text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pembelian.store') }}" method="POST" id="form-pembelian">
        @csrf

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8 space-y-6">

                {{-- Header Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Pemasok <span class="text-red-500">*</span>
                        </label>
                        <select name="pemasok_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">-- Pilih Pemasok --</option>
                            @foreach($pemasoks as $pemasok)
                                <option value="{{ $pemasok->id }}" {{ old('pemasok_id') == $pemasok->id ? 'selected' : '' }}>
                                    {{ $pemasok->nama_distributor }}
                                </option>
                            @endforeach
                        </select>
                        @error('pemasok_id')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        @error('tanggal')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="2"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="Catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                {{-- Detail Barang --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Detail Barang</h3>
                        <button type="button" id="btn-tambah-baris"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:hover:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Baris
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Produk</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 w-28">Qty</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 w-36">Harga Beli</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 w-36">Subtotal</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 w-16">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail-body"></tbody>
                            <tfoot>
                                <tr class="bg-gray-50 dark:bg-gray-900/50">
                                    <td colspan="3" class="px-4 py-3 text-right font-semibold text-gray-700 dark:text-gray-300">Total</td>
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white" id="grand-total">Rp 0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                        Simpan Pembelian
                    </button>
                    <a href="{{ route('pembelian.index') }}"
                       class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-xl transition">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Template baris --}}
<template id="template-baris">
    <tr class="border-t border-gray-200 dark:border-gray-700">
        <td class="px-4 py-3">
            <select name="produk_id[]" required
                class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
                <option value="">-- Pilih Produk --</option>
                @foreach($produks as $produk)
                    <option value="{{ $produk->id }}">{{ $produk->nama }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-4 py-3">
            <input type="number" name="qty[]" min="1" value="1" required
                class="qty w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </td>
        <td class="px-4 py-3">
            <input type="number" name="harga_beli[]" min="0" step="100" value="0" required
                class="harga w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-indigo-500 outline-none">
        </td>
        <td class="px-4 py-3">
            <input type="text" class="subtotal w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-white text-sm" readonly value="0">
        </td>
        <td class="px-4 py-3 text-center">
            <button type="button" class="btn-hapus-baris p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </td>
    </tr>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('detail-body');
    const template = document.getElementById('template-baris');
    const btnTambah = document.getElementById('btn-tambah-baris');
    const grandTotalEl = document.getElementById('grand-total');

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungSubtotal(row) {
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const harga = parseFloat(row.querySelector('.harga').value) || 0;
        const subtotal = qty * harga;
        row.querySelector('.subtotal').value = formatRupiah(subtotal);
        return subtotal;
    }

    function hitungGrandTotal() {
        let total = 0;
        tbody.querySelectorAll('tr').forEach(row => total += hitungSubtotal(row));
        grandTotalEl.textContent = formatRupiah(total);
    }

    function tambahBaris() {
        tbody.appendChild(template.content.cloneNode(true));
        hitungGrandTotal();
    }

    tambahBaris();
    btnTambah.addEventListener('click', tambahBaris);

    tbody.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty') || e.target.classList.contains('harga')) {
            hitungGrandTotal();
        }
    });

    tbody.addEventListener('click', function (e) {
        if (e.target.closest('.btn-hapus-baris')) {
            const row = e.target.closest('tr');
            if (tbody.querySelectorAll('tr').length > 1) {
                row.remove();
                hitungGrandTotal();
            } else {
                alert('Minimal harus ada 1 baris produk.');
            }
        }
    });
});
</script>
@endsection