<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tanggalHariIni = Carbon::now();

        // ======================
        // RINGKASAN HARI INI
        // ======================
        $totalPenjualanHariIni = Penjualan::whereDate('created_at', today())
            ->sum('total_pembayaran');

        $jumlahTransaksiHariIni = Penjualan::whereDate('created_at', today())
            ->count();

       $totalTunaiHariIni = Penjualan::whereDate('created_at', today())
    ->where('metode_pembayaran', 'CASH')
    ->sum('total_pembayaran');

$totalNonTunaiHariIni = Penjualan::whereDate('created_at', today())
    ->whereIn('metode_pembayaran', ['TRANSFER', 'QRIS'])
    ->sum('total_pembayaran');

        // ======================
        // STOK KRITIS
        // ======================
        $produkStokRendah = Produk::where('stok', '>', 0)
            ->where('stok', '<=', 10)
            ->orderBy('stok')
            ->paginate(5, ['*'], 'stok_rendah');

        $produkStokHabis = Produk::where('stok', '<=', 0)
            ->orderBy('nama')
            ->paginate(5, ['*'], 'stok_habis');

        // ======================
        // BEST SELLER
        // ======================
        $produkTerlaris = Produk::select(
                'produk.id',
                'produk.nama',
                'produk.stok',
                DB::raw('COALESCE(SUM(item_penjualan.kuantitas), 0) as total_terjual')
            )
            ->leftJoin('item_penjualan', 'produk.id', '=', 'item_penjualan.produk_id')
            ->groupBy('produk.id', 'produk.nama', 'produk.stok')
            ->orderByDesc('total_terjual')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
    'tanggalHariIni',
    'totalPenjualanHariIni',
    'jumlahTransaksiHariIni',
    'totalTunaiHariIni',      // ← diganti
    'totalNonTunaiHariIni',   // ← diganti
    'produkStokRendah',
    'produkStokHabis',
    'produkTerlaris'
));
    }
} 