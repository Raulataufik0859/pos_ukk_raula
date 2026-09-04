<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $bulanIni = now()->format('Y-m');

        // Statistik hari ini
        $penjualanHariIni = Penjualan::where('status', 'COMPLETED')
            ->whereDate('created_at', $today)
            ->sum('total_pembayaran');

        $transaksiHariIni = Penjualan::where('status', 'COMPLETED')
            ->whereDate('created_at', $today)
            ->count();

        $penjualanBulanIni = Penjualan::where('status', 'COMPLETED')
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$bulanIni])
            ->sum('total_pembayaran');

        $stokRendah = Produk::where('stok', '<=', 10)->where('stok', '>', 0)->count();
        $stokHabis  = Produk::where('stok', 0)->count();
        $totalProduk = Produk::count();

        // Chart data 7 hari terakhir
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = now()->subDays($i);
            $chartLabels[] = $tgl->translatedFormat('D d');
            $chartData[] = (float) Penjualan::where('status', 'COMPLETED')
                ->whereDate('created_at', $tgl->toDateString())
                ->sum('total_pembayaran');
        }

        // Top produk
        $topProduk = ItemPenjualan::select('produk_id', DB::raw('SUM(kuantitas) as total_qty'))
            ->with('produk')
            ->groupBy('produk_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('laporan.index', compact(
            'penjualanHariIni',
            'transaksiHariIni',
            'penjualanBulanIni',
            'stokRendah',
            'stokHabis',
            'totalProduk',
            'chartLabels',
            'chartData',
            'topProduk'
        ));
    }

    public function penjualan(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal ?? date('Y-m-01');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $penjualans = Penjualan::with('user')
            ->where('status', 'COMPLETED')
            ->whereBetween('created_at', [$tanggalAwal . ' 00:00:00', $tanggalAkhir . ' 23:59:59'])
            ->latest()
            ->get();

        $totalPenjualan = $penjualans->sum('total_pembayaran');

        return view('laporan.penjualan', compact(
            'penjualans',
            'tanggalAwal',
            'tanggalAkhir',
            'totalPenjualan'
        ));
    }

    public function pembelian(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal ?? date('Y-m-01');
        $tanggalAkhir = $request->tanggal_akhir ?? date('Y-m-d');

        $pembelians = Pembelian::with(['pemasok', 'user'])
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->latest()
            ->get();

        $totalPembelian = $pembelians->sum('total');

        return view('laporan.pembelian', compact(
            'pembelians',
            'tanggalAwal',
            'tanggalAkhir',
            'totalPembelian'
        ));
    }

    public function stok()
    {
        $produks = Produk::with('kategori')
            ->orderBy('stok')
            ->get();

        return view('laporan.stok', compact('produks'));
    }
}
