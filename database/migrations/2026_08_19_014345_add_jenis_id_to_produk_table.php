<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Hapus kolom lama 'jenis' (string) jika masih ada
            if (Schema::hasColumn('produk', 'jenis')) {
                $table->dropColumn('jenis');
            }

            // Tambah jenis_id
            $table->foreignId('jenis_id')
                ->nullable()
                ->after('nama')
                ->constrained('jenis')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropForeign(['jenis_id']);
            $table->dropColumn('jenis_id');

            $table->string('jenis')->nullable()->after('nama');
        });
    }
};
