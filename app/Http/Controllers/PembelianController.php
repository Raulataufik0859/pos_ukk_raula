<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Pemasok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelians = Pembelian::with(['pemasok', 'user'])
            ->latest()
            ->paginate(10);

        return view('pembelian.index', compact('pembelians'));
    }

    public function create()
    {
        $pemasoks = Pemasok::orderBy('nama_distributor')->get();
        $produks  = Produk::orderBy('nama')->get();

        return view('pembelian.create', compact('pemasoks', 'produks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id'   => 'required|exists:pemasok,id',
            'tanggal'      => 'required|date',
            'keterangan'   => 'nullable|string',
            'produk_id'    => 'required|array|min:1',
            'produk_id.*'  => 'required|exists:produk,id',
            'qty'          => 'required|array',
            'qty.*'        => 'required|integer|min:1',
            'harga_beli'   => 'required|array',
            'harga_beli.*' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Hitung total
            $total = 0;
            foreach ($request->produk_id as $index => $produkId) {
                $total += $request->qty[$index] * $request->harga_beli[$index];
            }

            // Simpan header pembelian
            $pembelian = Pembelian::create([
                'pemasok_id'  => $request->pemasok_id,
                'tanggal'     => $request->tanggal,
                'total'       => $total,
                'keterangan'  => $request->keterangan,
                'user_id'     => Auth::id(),
            ]);

            // Simpan detail + update stok
            foreach ($request->produk_id as $index => $produkId) {
                $qty       = $request->qty[$index];
                $hargaBeli = $request->harga_beli[$index];
                $subtotal  = $qty * $hargaBeli;

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id'    => $produkId,
                    'qty'          => $qty,
                    'harga_beli'   => $hargaBeli,
                    'subtotal'     => $subtotal,
                ]);

                // Tambah stok produk
                $produk = Produk::find($produkId);
                if ($produk) {
                    $produk->increment('stok', $qty);
                }
            }

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Pembelian $pembelian)
    {
        $pembelian->load(['pemasok', 'user', 'details.produk']);
        return view('pembelian.show', compact('pembelian'));
    }

    public function destroy(Pembelian $pembelian)
    {
        try {
            DB::beginTransaction();

            // Kembalikan stok
            foreach ($pembelian->details as $detail) {
                $produk = Produk::find($detail->produk_id);
                if ($produk) {
                    $produk->decrement('stok', $detail->qty);
                }
            }

            $pembelian->delete(); // detail ikut terhapus karena cascade

            DB::commit();

            return redirect()->route('pembelian.index')
                ->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}