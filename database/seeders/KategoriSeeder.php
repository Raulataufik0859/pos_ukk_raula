<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::whereRaw('LOWER(name) = ?', ['admin'])->first();
        $adminId = User::when($adminRole, fn ($q) => $q->where('role_id', $adminRole->id))
            ->orderBy('id')
            ->value('id')
            ?? User::orderBy('id')->value('id');

        $kategoris = [
            'Makanan',
            'Minuman',
            'Sepatu',
        ];

        foreach ($kategoris as $nama) {
            $kat = Kategori::firstOrCreate(
                ['nama' => $nama],
                ['user_id' => $adminId]
            );

            // Pastikan user_id terisi (termasuk data lama / firstOrCreate yang sudah ada)
            if ($adminId && empty($kat->user_id)) {
                $kat->update(['user_id' => $adminId]);
            }
        }
    }
}
