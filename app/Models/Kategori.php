<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';   // ← penting! karena tabel sudah di-rename

    protected $fillable = [
        'nama',          // sesuaikan dengan kolom yang ada di tabel
        // tambahkan kolom lain jika ada
    ];

    /**
     * Relasi ke Produk.
     * Catatan: di tabel produk kolom foreign key-nya bernama 'jenis_id'
     * (bukan kategori_id), jadi kita mapping ke situ.
     */
    public function produks()
    {
        return $this->hasMany(Produk::class, 'jenis_id');
    }
}
