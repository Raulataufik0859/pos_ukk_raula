<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(SearchRequest $request)
    {
        $user = Auth::user();
        $keyword = $request->input('search');

        $sales = Penjualan::with('user')
            ->when($user->role->name === 'kasir', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('penjualan.index', compact('sales'));
    }

    public function create(SearchRequest $request)
    {
        // Ambil transaksi OPEN milik user yang paling baru
        $sale = Penjualan::where('user_id', Auth::id())
            ->where('status', 'OPEN')
            ->latest()
            ->first();

        // Jika tidak ada, buat baru
        if (!$sale) {
            $sale = Penjualan::create([
                'user_id'           => Auth::id(),
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH',
                'status'            => 'OPEN',
            ]);
        }

        $keyword = $request->input('search');
        $kategoriId = $request->input('kategori');

        $products = Produk::with('kategori')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', '%' . $keyword . '%');
            })
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('jenis_id', $kategoriId);
            })
            ->where('stok', '>', 0)
            ->orderBy('nama')
            ->get();

        $kategoris = \App\Models\Kategori::orderBy('nama')->get();

        // Load item keranjang
        $sale->load('itemPenjualan.produk');

        return view('penjualan.form', compact('sale', 'products', 'kategoris'));
    }

    public function store(Request $request)
    {
        // Biasanya item ditambah lewat route terpisah (ItemPenjualanController)
        // Method ini bisa dipakai kalau ingin create + checkout sekaligus
        return redirect()->route('penjualan.create');
    }

    public function show(Penjualan $penjualan)
    {
        $penjualan->load(['user', 'itemPenjualan.produk']);

        return view('penjualan.show', compact('penjualan'));
    }

    public function edit(Penjualan $penjualan)
{
    abort_if($penjualan->status === 'COMPLETED', 403, 'Transaksi sudah selesai.');

    $sale = $penjualan;
    $sale->load('itemPenjualan.produk');

    $products = Produk::where('stok', '>', 0)->orderBy('nama')->get();
    $mode = 'edit';

    return view('penjualan.form', compact('sale', 'products', 'mode'));
}

    public function update(Request $request, Penjualan $penjualan)
{
    $request->validate([
        'metode_pembayaran' => 'required|in:CASH,QRIS,TRANSFER',
        'bank_transfer'     => 'nullable|string|max:50',
        'uang_diterima'     => 'nullable|numeric|min:0',
    ]);

    // Validasi manual sesuai metode
    if ($request->metode_pembayaran === 'TRANSFER' && empty($request->bank_transfer)) {
        return back()->with('error', 'Silakan pilih bank transfer terlebih dahulu.');
    }

    if ($request->metode_pembayaran === 'CASH') {
        $uang = (float) $request->uang_diterima;
        $total = (float) $penjualan->itemPenjualan()->sum('subtotal');
        if ($uang < $total) {
            return back()->with('error', 'Uang diterima kurang dari total belanja.');
        }
    }

    if ($penjualan->status !== 'OPEN') {
        return back()->with('error', 'Transaksi sudah diproses.');
    }

    if ($penjualan->itemPenjualan()->count() === 0) {
        return back()->with('error', 'Keranjang masih kosong.');
    }

    try {
        DB::transaction(function () use ($penjualan, $request) {
            $total = $penjualan->itemPenjualan()->sum('subtotal');

            $data = [
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_pembayaran'  => $total,
                'status'            => 'COMPLETED',
            ];

            // Simpan bank jika kolomnya ada
            if ($request->filled('bank_transfer') && \Schema::hasColumn('penjualan', 'bank_transfer')) {
                $data['bank_transfer'] = $request->bank_transfer;
            }

            // Simpan uang diterima jika kolomnya ada
            if ($request->filled('uang_diterima') && \Schema::hasColumn('penjualan', 'uang_diterima')) {
                $data['uang_diterima'] = $request->uang_diterima;
            }

            $penjualan->update($data);

            // Kurangi stok
            foreach ($penjualan->itemPenjualan as $item) {
                $produk = $item->produk;
                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $item->kuantitas);
                    $produk->save();
                }
            }
        });

        return redirect()
            ->route('penjualan.show', $penjualan)
            ->with('success', 'Transaksi berhasil diselesaikan.')
            ->with('print', true);
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function destroy(Penjualan $penjualan)
    {
        if ($penjualan->status !== 'OPEN') {
            return redirect()
                ->route('penjualan.index')
                ->with('error', 'Transaksi yang sudah selesai tidak bisa dibatalkan.');
        }

        try {
            DB::transaction(function () use ($penjualan) {
                // Kembalikan stok (kalau sebelumnya sudah dikurangi saat tambah item)
                // Sesuaikan logika ini dengan alur ItemPenjualanController kamu
                foreach ($penjualan->itemPenjualan as $item) {
                    if ($item->produk) {
                        $item->produk->increment('stok', $item->kuantitas);
                    }
                }

                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            });

            return redirect()
                ->route('penjualan.index')
                ->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}