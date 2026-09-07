<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aman untuk project yang:
     * - masih pakai tabel "jenis", ATAU
     * - sudah rename menjadi "kategori"
     */
    public function up(): void
    {
        // 1) Rename jenis → kategori (jika belum)
        if (Schema::hasTable('jenis') && !Schema::hasTable('kategori')) {
            Schema::rename('jenis', 'kategori');
        }

        // 2) Jika keduanya belum ada, buat tabel kategori
        if (!Schema::hasTable('kategori') && !Schema::hasTable('jenis')) {
            Schema::create('kategori', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->timestamps();
            });
        }

        // 3) Tambah user_id pada tabel yang aktif
        $tableName = Schema::hasTable('kategori') ? 'kategori' : 'jenis';

        if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'user_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tableName = Schema::hasTable('kategori') ? 'kategori' : 'jenis';

        if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
    }
};
