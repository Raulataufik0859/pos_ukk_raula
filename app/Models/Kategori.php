<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Schema;

class Kategori extends Model
{
    use HasFactory;

    /**
     * Tabel bisa "kategori" (setelah rename) atau "jenis" (belum rename).
     */
    public function getTable()
    {
        if (Schema::hasTable('kategori')) {
            return 'kategori';
        }
        if (Schema::hasTable('jenis')) {
            return 'jenis';
        }
        return 'kategori';
    }

    protected $fillable = [
        'nama',
        'user_id',
    ];

    public function produks()
    {
        return $this->hasMany(Produk::class, 'jenis_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
