<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum status agar mendukung PENDING (MySQL)
        try {
            DB::statement("ALTER TABLE penjualan MODIFY COLUMN status ENUM('OPEN','PENDING','COMPLETED') NOT NULL DEFAULT 'OPEN'");
        } catch (\Throwable $e) {
            // SQLite / driver lain: skip
        }

        Schema::table('penjualan', function (Blueprint $table) {
            if (!Schema::hasColumn('penjualan', 'bank_transfer')) {
                $table->string('bank_transfer')->nullable()->after('metode_pembayaran');
            }
            if (!Schema::hasColumn('penjualan', 'nama_pengirim')) {
                $table->string('nama_pengirim')->nullable()->after('bank_transfer');
            }
            if (!Schema::hasColumn('penjualan', 'uang_diterima')) {
                $table->decimal('uang_diterima', 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            if (Schema::hasColumn('penjualan', 'nama_pengirim')) {
                $table->dropColumn('nama_pengirim');
            }
        });
    }
};
