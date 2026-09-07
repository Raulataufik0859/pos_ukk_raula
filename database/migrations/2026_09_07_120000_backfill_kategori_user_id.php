<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $table = Schema::hasTable('kategori') ? 'kategori' : (Schema::hasTable('jenis') ? 'jenis' : null);
        if (!$table || !Schema::hasColumn($table, 'user_id')) {
            return;
        }

        // Isi user_id kosong dengan admin pertama (atau user id 1)
        $adminId = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->whereRaw('LOWER(roles.name) = ?', ['admin'])
            ->value('users.id');

        if (!$adminId) {
            $adminId = DB::table('users')->orderBy('id')->value('id');
        }

        if ($adminId) {
            DB::table($table)->whereNull('user_id')->update(['user_id' => $adminId]);
        }
    }

    public function down(): void
    {
        // tidak perlu rollback data
    }
};
