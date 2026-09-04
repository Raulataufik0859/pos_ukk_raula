<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('jenis') && !Schema::hasTable('kategori')) {
            Schema::rename('jenis', 'kategori');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kategori') && !Schema::hasTable('jenis')) {
            Schema::rename('kategori', 'jenis');
        }
    }
};
