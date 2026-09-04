<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Penjualan;
use App\Models\Produk;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualans';

    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'harga',
        'qty',
        'subtotal'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Penjualan
    |--------------------------------------------------------------------------
    */

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Produk
    |--------------------------------------------------------------------------
    */

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}