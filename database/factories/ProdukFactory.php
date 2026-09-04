<?php

namespace Database\Factories;

use App\Models\Jenis;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(10_000, 500_000);

        return [
            'user_id'    => User::where('role_id', 1)->inRandomOrder()->value('id') ?? 1,
            'foto'       => null, // biar kosong dulu, atau bisa diisi path dummy
            'nama'       => $this->faker->words(2, true),
            'jenis_id'   => Jenis::inRandomOrder()->value('id'),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(5_000, 150_000),
            'stok'       => $this->faker->numberBetween(5, 300),
        ];
    }
}
