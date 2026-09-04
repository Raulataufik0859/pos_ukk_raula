<?php

namespace App\Http\Controllers;

use App\Models\ItemPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemPenjualanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:produk,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $produk = Produk::lockForUpdate()->findOrFail($request->product_id);

                $sale = Penjualan::where('user_id', auth()->id())
                    ->where('status', 'OPEN')
                    ->firstOrFail();

                if ($produk->stok < $request->quantity) {
                    throw new \Exception('Stok tidak mencukupi! Sisa stok: ' . $produk->stok);
                }

                $item = ItemPenjualan::where('penjualan_id', $sale->id)
                    ->where('produk_id', $produk->id)
                    ->first();

                if ($item) {
                    $newQty = $item->kuantitas + $request->quantity;

                    if ($produk->stok < $request->quantity) {
                        throw new \Exception('Stok tidak mencukupi!');
                    }

                    $item->kuantitas = $newQty;
                    $item->subtotal  = $newQty * $item->harga_satuan;
                    $item->save();
                } else {
                    ItemPenjualan::create([
                        'penjualan_id'  => $sale->id,
                        'produk_id'     => $produk->id,
                        'kuantitas'     => $request->quantity,
                        'harga_satuan'  => $produk->harga_jual,
                        'subtotal'      => $produk->harga_jual * $request->quantity,
                    ]);
                }

                // Opsional: kurangi stok saat ditambahkan ke keranjang
                // $produk->decrement('stok', $request->quantity);

                $sale->update([
                    'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal'),
                ]);
            });

            return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $item = ItemPenjualan::findOrFail($id);
                $produk = $item->produk()->lockForUpdate()->first();

                $selisih = $request->quantity - $item->kuantitas;

                if ($selisih > 0 && $produk->stok < $selisih) {
                    throw new \Exception('Stok tidak mencukupi! Sisa stok: ' . $produk->stok);
                }

                // Jika kamu mengurangi stok saat tambah item, aktifkan baris ini:
                // if ($selisih > 0) $produk->decrement('stok', $selisih);
                // if ($selisih < 0) $produk->increment('stok', abs($selisih));

                $item->update([
                    'kuantitas' => $request->quantity,
                    'subtotal'  => $request->quantity * $item->harga_satuan,
                ]);

                $item->penjualan->update([
                    'total_pembayaran' => $item->penjualan->itemPenjualan()->sum('subtotal'),
                ]);
            });

            return back()->with('success', 'Item berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(ItemPenjualan $itempenjualan)
    {
        try {
            DB::transaction(function () use ($itempenjualan) {
                $sale = $itempenjualan->penjualan;

                // Jika stok sudah dikurangi saat tambah, kembalikan di sini:
                // $itempenjualan->produk?->increment('stok', $itempenjualan->kuantitas);

                $itempenjualan->delete();

                $sale->update([
                    'total_pembayaran' => $sale->itemPenjualan()->sum('subtotal'),
                ]);
            });

            return back()->with('success', 'Item dihapus dari keranjang.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}