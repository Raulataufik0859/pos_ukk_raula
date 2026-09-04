<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::where('role_id', 1)->value('id') ?? 1;

        // ====================== DATA REALISTIS ======================
        $data = [
            'Sepatu' => [
                ['nama' => 'Adidas Samba',           'harga_beli' => 650000, 'harga_jual' => 950000, 'stok' => 25],
                ['nama' => 'Nike Air Force 1',        'harga_beli' => 780000, 'harga_jual' => 1200000, 'stok' => 18],
                ['nama' => 'Converse Chuck 70',       'harga_beli' => 520000, 'harga_jual' => 850000, 'stok' => 30],
                ['nama' => 'Vans Old Skool',          'harga_beli' => 480000, 'harga_jual' => 750000, 'stok' => 22],
                ['nama' => 'New Balance 550',         'harga_beli' => 890000, 'harga_jual' => 1350000, 'stok' => 12],
                ['nama' => 'Puma Suede Classic',      'harga_beli' => 450000, 'harga_jual' => 720000, 'stok' => 28],
                ['nama' => 'Reebok Club C 85',        'harga_beli' => 510000, 'harga_jual' => 800000, 'stok' => 15],
                ['nama' => 'Adidas Superstar',        'harga_beli' => 600000, 'harga_jual' => 920000, 'stok' => 20],
                ['nama' => 'Nike Dunk Low',           'harga_beli' => 950000, 'harga_jual' => 1450000, 'stok' => 10],
                ['nama' => 'Compass Gazelle Low',     'harga_beli' => 320000, 'harga_jual' => 499000, 'stok' => 35],
            ],

            'Makanan' => [
                ['nama' => 'Nasi Goreng Spesial',     'harga_beli' => 12000, 'harga_jual' => 25000, 'stok' => 80],
                ['nama' => 'Ayam Geprek Keju',        'harga_beli' => 15000, 'harga_jual' => 28000, 'stok' => 60],
                ['nama' => 'Mie Ayam Bakso',          'harga_beli' => 13000, 'harga_jual' => 22000, 'stok' => 70],
                ['nama' => 'Soto Ayam Lamongan',      'harga_beli' => 14000, 'harga_jual' => 25000, 'stok' => 55],
                ['nama' => 'Bakso Urat Komplit',      'harga_beli' => 16000, 'harga_jual' => 30000, 'stok' => 45],
                ['nama' => 'Nasi Padang Rendang',     'harga_beli' => 18000, 'harga_jual' => 32000, 'stok' => 40],
                ['nama' => 'Gado-gado Siram',         'harga_beli' => 10000, 'harga_jual' => 18000, 'stok' => 90],
                ['nama' => 'Sate Ayam 10 Tusuk',      'harga_beli' => 20000, 'harga_jual' => 35000, 'stok' => 50],
                ['nama' => 'Pecel Lele + Nasi',       'harga_beli' => 13000, 'harga_jual' => 23000, 'stok' => 65],
                ['nama' => 'Ayam Bakar Madu',         'harga_beli' => 22000, 'harga_jual' => 38000, 'stok' => 35],
            ],

            'Minuman' => [
                ['nama' => 'Es Teh Manis Jumbo',      'harga_beli' => 3000,  'harga_jual' => 8000,  'stok' => 150],
                ['nama' => 'Kopi Susu Gula Aren',     'harga_beli' => 8000,  'harga_jual' => 18000, 'stok' => 100],
                ['nama' => 'Juice Alpukat',           'harga_beli' => 10000, 'harga_jual' => 20000, 'stok' => 70],
                ['nama' => 'Es Jeruk Peras',          'harga_beli' => 5000,  'harga_jual' => 12000, 'stok' => 120],
                ['nama' => 'Thai Tea',                'harga_beli' => 7000,  'harga_jual' => 15000, 'stok' => 90],
                ['nama' => 'Matcha Latte',            'harga_beli' => 9000,  'harga_jual' => 20000, 'stok' => 60],
                ['nama' => 'Air Mineral 600ml',       'harga_beli' => 2000,  'harga_jual' => 5000,  'stok' => 200],
                ['nama' => 'Es Cincau Hijau',         'harga_beli' => 4000,  'harga_jual' => 10000, 'stok' => 80],
                ['nama' => 'Milkshake Coklat',        'harga_beli' => 9000,  'harga_jual' => 18000, 'stok' => 55],
                ['nama' => 'Lemon Tea',               'harga_beli' => 5000,  'harga_jual' => 12000, 'stok' => 110],
            ],
        ];

        foreach ($data as $namaKategori => $produks) {
            $kategori = Kategori::where('nama', $namaKategori)->first();

            if (!$kategori) {
                continue;
            }

            foreach ($produks as $item) {
                Produk::create([
                    'user_id'    => $userId,
                    'foto'       => null,
                    'nama'       => $item['nama'],
                    'jenis_id'   => $kategori->id,   // kolom masih bernama jenis_id
                    'harga_beli' => $item['harga_beli'],
                    'harga_jual' => $item['harga_jual'],
                    'stok'       => $item['stok'],
                ]);
            }
        }
    }
}