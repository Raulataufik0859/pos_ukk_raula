<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isKasir = strtolower($user->role->name ?? '') === 'kasir';
        $tanggalHariIni = Carbon::now();

        // Statistik: HANYA status COMPLETED (lunas)
        // Kasir: hanya transaksi miliknya sendiri
        $penjualanQuery = Penjualan::query()
            ->where('status', 'COMPLETED')
            ->when($isKasir, fn ($q) => $q->where('user_id', $user->id));

        $totalPenjualanHariIni = (clone $penjualanQuery)
            ->whereDate('created_at', today())
            ->sum('total_pembayaran');

        $jumlahTransaksiHariIni = (clone $penjualanQuery)
            ->whereDate('created_at', today())
            ->count();

        $totalTunaiHariIni = (clone $penjualanQuery)
            ->whereDate('created_at', today())
            ->where('metode_pembayaran', 'CASH')
            ->sum('total_pembayaran');

        $totalNonTunaiHariIni = (clone $penjualanQuery)
            ->whereDate('created_at', today())
            ->whereIn('metode_pembayaran', ['TRANSFER', 'QRIS'])
            ->sum('total_pembayaran');

        // Stok: admin & kasir sama-sama bisa melihat (kasir butuh info stok saat jualan)
        $produkStokRendah = Produk::where('stok', '>', 0)
            ->where('stok', '<=', 10)
            ->orderBy('stok')
            ->paginate(5, ['*'], 'stok_rendah');

        $produkStokHabis = Produk::where('stok', '<=', 0)
            ->orderBy('nama')
            ->paginate(5, ['*'], 'stok_habis');

        // Produk terlaris: dari item penjualan yang COMPLETED
        // Kasir: hanya dari transaksi sendiri
        $produkTerlarisQuery = Produk::select(
                'produk.id',
                'produk.nama',
                'produk.stok',
                DB::raw('COALESCE(SUM(item_penjualan.kuantitas), 0) as total_terjual')
            )
            ->leftJoin('item_penjualan', 'produk.id', '=', 'item_penjualan.produk_id')
            ->leftJoin('penjualan', 'item_penjualan.penjualan_id', '=', 'penjualan.id')
            ->where(function ($q) {
                $q->where('penjualan.status', 'COMPLETED')
                  ->orWhereNull('penjualan.id');
            });

        if ($isKasir) {
            $produkTerlarisQuery->where(function ($q) use ($user) {
                $q->where('penjualan.user_id', $user->id)
                  ->orWhereNull('penjualan.id');
            });
        }

        $produkTerlaris = $produkTerlarisQuery
            ->groupBy('produk.id', 'produk.nama', 'produk.stok')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get()
            ->filter(fn ($p) => ($p->total_terjual ?? 0) > 0)
            ->values();

        return view('dashboard', compact(
            'tanggalHariIni',
            'totalPenjualanHariIni',
            'jumlahTransaksiHariIni',
            'totalTunaiHariIni',
            'totalNonTunaiHariIni',
            'produkStokRendah',
            'produkStokHabis',
            'produkTerlaris',
            'isKasir'
        ));
    }
}
