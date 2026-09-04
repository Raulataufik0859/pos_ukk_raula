@extends('layouts.app')

@section('content')
<div class="p-4 lg:p-6 h-[calc(100vh-80px)] flex flex-col">

    {{-- Top bar --}}
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Buat Transaksi</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pilih produk dan proses pembayaran</p>
        </div>
        <a href="{{ route('penjualan.index') }}"
           class="px-4 py-2 text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl transition">
            ← Kembali
        </a>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-4 min-h-0">

        {{-- LEFT: Product List --}}
        <div class="lg:col-span-2 flex flex-col min-h-0 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Search --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="search-produk" placeholder="Cari produk atau scan barcode..."
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
            </div>

            {{-- Product grid --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-2" id="product-list">
                @foreach($produks as $produk)
                    <div class="product-item flex items-center justify-between p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 bg-gray-50 dark:bg-gray-900/50 transition cursor-pointer"
                         data-id="{{ $produk->id }}"
                         data-nama="{{ $produk->nama }}"
                         data-harga="{{ $produk->harga_jual }}"
                         data-stok="{{ $produk->stok }}">
                        <div class="min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white truncate">{{ $produk->nama }}</p>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">
                                Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Stok: {{ $produk->stok }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <input type="number" min="1" max="{{ $produk->stok }}" value="1"
                                   class="qty-input w-14 px-2 py-1.5 text-center text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                   onclick="event.stopPropagation()">
                            <button type="button"
                                    class="btn-add w-9 h-9 flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition"
                                    onclick="event.stopPropagation(); addToCart(this.closest('.product-item'))">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: Cart --}}
        <div class="flex flex-col min-h-0 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900 dark:text-white">Keranjang</h2>
                <span id="cart-count" class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                    0 item
                </span>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
                <p id="cart-empty" class="text-center text-sm text-gray-400 py-10">Keranjang masih kosong</p>
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
                    <span id="cart-total" class="text-xl font-bold text-gray-900 dark:text-white">Rp 0</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Metode Pembayaran</label>
                    <select id="metode-pembayaran"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <option value="">-- Pilih Metode --</option>
                        <option value="cash">Cash</option>
                        <option value="qris">QRIS</option>
                        <option value="transfer">Transfer</option>
                    </select>
                </div>

                <button type="button" id="btn-checkout"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-medium rounded-xl transition shadow-sm">
                    Checkout
                </button>

                <button type="button" id="btn-batal"
                        class="w-full py-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 text-sm font-medium rounded-xl transition">
                    Batalkan Transaksi
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form untuk submit --}}
<form id="form-checkout" action="{{ route('penjualan.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="metode_pembayaran" id="input-metode">
    <div id="input-items"></div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let cart = {}; // { produkId: { id, nama, harga, qty, stok } }

    const cartItemsEl = document.getElementById('cart-items');
    const cartEmptyEl = document.getElementById('cart-empty');
    const cartCountEl = document.getElementById('cart-count');
    const cartTotalEl = document.getElementById('cart-total');
    const metodeEl = document.getElementById('metode-pembayaran');
    const btnCheckout = document.getElementById('btn-checkout');

    function formatRp(n) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(n);
    }

    function renderCart() {
        const items = Object.values(cart);
        cartCountEl.textContent = items.reduce((s, i) => s + i.qty, 0) + ' item';

        if (items.length === 0) {
            cartItemsEl.innerHTML = '<p id="cart-empty" class="text-center text-sm text-gray-400 py-10">Keranjang masih kosong</p>';
            cartTotalEl.textContent = 'Rp 0';
            return;
        }

        let total = 0;
        cartItemsEl.innerHTML = items.map(item => {
            const sub = item.harga * item.qty;
            total += sub;
            return `
                <div class="flex items-start justify-between gap-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                    <div class="min-w-0 flex-1">
                        <p class="font-medium text-gray-900 dark:text-white text-sm truncate">${item.nama}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${formatRp(item.harga)} × ${item.qty}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-semibold text-gray-900 dark:text-white text-sm">${formatRp(sub)}</p>
                        <div class="flex items-center gap-1 mt-1">
                            <input type="number" min="1" max="${item.stok}" value="${item.qty}"
                                   class="w-12 px-1 py-0.5 text-center text-xs rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                                   onchange="updateQty(${item.id}, this.value)">
                            <button type="button" onclick="removeItem(${item.id})"
                                    class="w-6 h-6 flex items-center justify-center text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded transition">
                                ×
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        cartTotalEl.textContent = formatRp(total);
    }

    window.addToCart = function (el) {
        const id = el.dataset.id;
        const nama = el.dataset.nama;
        const harga = parseFloat(el.dataset.harga);
        const stok = parseInt(el.dataset.stok);
        const qtyInput = el.querySelector('.qty-input');
        let qty = parseInt(qtyInput.value) || 1;

        if (qty > stok) {
            alert('Stok tidak cukup. Sisa stok: ' + stok);
            return;
        }

        if (cart[id]) {
            const newQty = cart[id].qty + qty;
            if (newQty > stok) {
                alert('Stok tidak cukup. Sisa stok: ' + stok);
                return;
            }
            cart[id].qty = newQty;
        } else {
            cart[id] = { id, nama, harga, qty, stok };
        }
        renderCart();
    };

    window.updateQty = function (id, val) {
        let qty = parseInt(val) || 1;
        if (qty < 1) qty = 1;
        if (qty > cart[id].stok) {
            alert('Stok tidak cukup');
            qty = cart[id].stok;
        }
        cart[id].qty = qty;
        renderCart();
    };

