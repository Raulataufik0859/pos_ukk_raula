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
        // Transaksi Baru (?baru=1): selalu buat transaksi OPEN baru
        // Tanpa param: lanjutkan OPEN terakhir jika ada, jika tidak buat baru
        if ($request->boolean('baru') || $request->boolean('new')) {
            $sale = Penjualan::create([
                'user_id'           => Auth::id(),
                'total_pembayaran'  => 0,
                'metode_pembayaran' => 'CASH',
                'status'            => 'OPEN',
            ]);
        } else {
            $sale = Penjualan::where('user_id', Auth::id())
                ->where('status', 'OPEN')
                ->latest()
                ->first();

            if (!$sale) {
                $sale = Penjualan::create([
                    'user_id'           => Auth::id(),
                    'total_pembayaran'  => 0,
                    'metode_pembayaran' => 'CASH',
                    'status'            => 'OPEN',
                ]);
            }
        }

        $keyword = $request->input('search');
        $kategoriId = $request->input('kategori');

        // Jika user mengetik nama kategori di search (mis. "Makanan"), aktifkan tab kategori itu
        if ($keyword && !$kategoriId) {
            $matchKat = \App\Models\Kategori::where('nama', 'like', $keyword)
                ->orWhere('nama', 'like', '%' . $keyword . '%')
                ->orderByRaw('CASE WHEN nama LIKE ? THEN 0 ELSE 1 END', [$keyword])
                ->first();
            // Prioritas exact match
            $exact = \App\Models\Kategori::whereRaw('LOWER(nama) = ?', [strtolower(trim($keyword))])->first();
            if ($exact) {
                $kategoriId = $exact->id;
                // Jangan filter nama produk lagi — cukup filter kategori
                $keyword = null;
            } elseif ($matchKat && str_contains(strtolower($matchKat->nama), strtolower(trim($keyword)))) {
                // Jika keyword mirip nama kategori (bukan nama produk spesifik)
                $produkHit = Produk::where('nama', 'like', '%' . $keyword . '%')->exists();
                if (!$produkHit) {
                    $kategoriId = $matchKat->id;
                    $keyword = null;
                }
            }
        }

        $products = Produk::with('kategori')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', '%' . $keyword . '%')
                      ->orWhereHas('kategori', function ($kq) use ($keyword) {
                          $kq->where('nama', 'like', '%' . $keyword . '%');
                      });
                });
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

        // Canonical URL: selalu /penjualan/{id}/edit agar keranjang tidak "nyasar"
        if (!request()->routeIs('penjualan.edit')) {
            return redirect()->route('penjualan.edit', array_filter([
                'penjualan' => $sale->id,
                'search' => request('search'),
                'kategori' => request('kategori'),
            ]));
        }

        return view('penjualan.form', compact('sale', 'products', 'kategoris', 'kategoriId'));
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

    public function edit(Penjualan $penjualan, \Illuminate\Http\Request $request)
    {
        abort_if(in_array($penjualan->status, ['COMPLETED', 'PENDING'], true), 403, 'Transaksi sudah diproses.');

        $sale = $penjualan;
        $sale->load('itemPenjualan.produk');

        $keyword = $request->input('search');
        $kategoriId = $request->input('kategori');

        // Jika user mengetik nama kategori di search (mis. "Makanan"), aktifkan tab kategori itu
        if ($keyword && !$kategoriId) {
            $matchKat = \App\Models\Kategori::where('nama', 'like', $keyword)
                ->orWhere('nama', 'like', '%' . $keyword . '%')
                ->orderByRaw('CASE WHEN nama LIKE ? THEN 0 ELSE 1 END', [$keyword])
                ->first();
            // Prioritas exact match
            $exact = \App\Models\Kategori::whereRaw('LOWER(nama) = ?', [strtolower(trim($keyword))])->first();
            if ($exact) {
                $kategoriId = $exact->id;
                // Jangan filter nama produk lagi — cukup filter kategori
                $keyword = null;
            } elseif ($matchKat && str_contains(strtolower($matchKat->nama), strtolower(trim($keyword)))) {
                // Jika keyword mirip nama kategori (bukan nama produk spesifik)
                $produkHit = Produk::where('nama', 'like', '%' . $keyword . '%')->exists();
                if (!$produkHit) {
                    $kategoriId = $matchKat->id;
                    $keyword = null;
                }
            }
        }

        $products = Produk::with('kategori')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', '%' . $keyword . '%')
                      ->orWhereHas('kategori', function ($kq) use ($keyword) {
                          $kq->where('nama', 'like', '%' . $keyword . '%');
                      });
                });
            })
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('jenis_id', $kategoriId);
            })
            ->where('stok', '>', 0)
            ->orderBy('nama')
            ->get();

        $kategoris = \App\Models\Kategori::orderBy('nama')->get();
        $mode = 'edit';

        return view('penjualan.form', compact('sale', 'products', 'kategoris', 'mode', 'kategoriId'));
    }

    public function update(Request $request, Penjualan $penjualan)
{
    $request->validate([
        'metode_pembayaran' => 'required|in:CASH,QRIS,TRANSFER',
        'bank_transfer'     => 'nullable|string|max:50',
        'bank'              => 'nullable|string|max:50',
        'uang_diterima'     => 'nullable|numeric|min:0',
    ]);

    $bankTransfer = $request->input('bank_transfer') ?: $request->input('bank');

    // Validasi manual sesuai metode
    if ($request->metode_pembayaran === 'TRANSFER' && empty($bankTransfer)) {
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
        DB::transaction(function () use ($penjualan, $request, $bankTransfer) {
            $total = $penjualan->itemPenjualan()->sum('subtotal');

            // CASH & QRIS: langsung lunas. TRANSFER: menunggu verifikasi (PENDING)
            $statusFinal = $request->metode_pembayaran === 'TRANSFER' ? 'PENDING' : 'COMPLETED';

            $data = [
                'metode_pembayaran' => $request->metode_pembayaran,
                'total_pembayaran'  => $total,
                'status'            => $statusFinal,
            ];

            if ($request->filled('nama_pengirim') && \Schema::hasColumn('penjualan', 'nama_pengirim')) {
                $data['nama_pengirim'] = $request->nama_pengirim;
            }

            // Simpan bank jika kolomnya ada
            if ($bankTransfer && \Schema::hasColumn('penjualan', 'bank_transfer')) {
                $data['bank_transfer'] = $bankTransfer;
            }

            // Simpan uang diterima jika kolomnya ada
            if ($request->filled('uang_diterima') && \Schema::hasColumn('penjualan', 'uang_diterima')) {
                $data['uang_diterima'] = $request->uang_diterima;
            }

            $penjualan->update($data);

            // Stok hanya dikurangi jika sudah COMPLETED (bukan PENDING transfer)
            if ($statusFinal === 'COMPLETED') {
                foreach ($penjualan->itemPenjualan as $item) {
                    $produk = $item->produk;
                    if ($produk) {
                        $produk->stok = max(0, $produk->stok - $item->kuantitas);
                        $produk->save();
                    }
                }
            }
        });

        $msg = $request->metode_pembayaran === 'TRANSFER'
            ? 'Transfer dicatat. Menunggu verifikasi admin (status PENDING).'
            : 'Transaksi berhasil diselesaikan.';

        return redirect()
            ->route('penjualan.show', $penjualan)
            ->with('success', $msg)
            ->with('print', $request->metode_pembayaran !== 'TRANSFER');
    } catch (\Exception $e) {
        return back()->with('error', 'Gagal: ' . $e->getMessage());
    }
}


    /** Admin: konfirmasi dana transfer masuk → COMPLETED + kurangi stok */
    public function confirmTransfer(Penjualan $penjualan)
    {
        abort_unless(strtolower(auth()->user()->role->name ?? '') === 'admin', 403);
        abort_if($penjualan->status !== 'PENDING', 403, 'Hanya transaksi PENDING yang bisa dikonfirmasi.');

        DB::transaction(function () use ($penjualan) {
            foreach ($penjualan->itemPenjualan as $item) {
                $produk = $item->produk;
                if ($produk) {
                    $produk->stok = max(0, $produk->stok - $item->kuantitas);
                    $produk->save();
                }
            }
            $penjualan->update(['status' => 'COMPLETED']);
        });

        return redirect()
            ->route('penjualan.show', $penjualan)
            ->with('success', 'Transfer dikonfirmasi. Transaksi selesai (COMPLETED).')
            ->with('print', true);
    }


    /** Admin: tolak transfer — stok belum berkurang (PENDING), cukup batalkan */
    public function rejectTransfer(Penjualan $penjualan)
    {
        abort_unless(strtolower(auth()->user()->role->name ?? '') === 'admin', 403, 'Hanya admin yang dapat menolak transfer.');
        abort_if($penjualan->status !== 'PENDING', 403, 'Hanya transaksi PENDING yang bisa ditolak.');

        DB::transaction(function () use ($penjualan) {
            // Stok TIDAK dikurangi saat PENDING, jadi tidak perlu restore
            $penjualan->itemPenjualan()->delete();
            $penjualan->delete();
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Transfer ditolak. Transaksi dibatalkan.');
    }

    public function destroy(Penjualan $penjualan)
    {
        if ($penjualan->status === 'COMPLETED') {
            return redirect()
                ->route('penjualan.index')
                ->with('error', 'Transaksi yang sudah selesai tidak bisa dibatalkan.');
        }

        try {
            DB::transaction(function () use ($penjualan) {
                /**
                 * Stok HANYA dikurangi saat status COMPLETED.
                 * OPEN / PENDING: stok belum pernah dikurangi → JANGAN increment (restore).
                 * COMPLETED tidak boleh dibatalkan (sudah diblok di atas).
                 */
                $penjualan->itemPenjualan()->delete();
                $penjualan->delete();
            });

            $params = [];
            if (request()->filled('return_query')) {
                parse_str(request()->input('return_query'), $params);
            } elseif ($ref = request()->headers->get('referer')) {
                $q = parse_url($ref, PHP_URL_QUERY);
                if ($q) parse_str($q, $params);
            }
            return redirect()->route('penjualan.index', $params)->with('success', 'Transaksi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}