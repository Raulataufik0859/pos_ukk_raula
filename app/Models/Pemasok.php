<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemasok extends Model
{
    use HasFactory;

    protected $table = 'pemasok';

   protected $fillable = [
        'nama_distributor',
        'no_telepon',
        'email',
        'alamat',
    ];

    public function pembelians()
    {
        return $this->hasMany(Pembelian::class, 'pemasok_id');
    }
}