<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah/penyesuaian kolom di tabel users
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom 'role' jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', 30)->default('user')->after('email');
            }

            // Tambahkan kolom 'division' jika belum ada
            if (!Schema::hasColumn('users', 'division')) {
                $table->string('division')->nullable()->after('role');
            }

            // Tambahkan kolom 'is_active' jika belum ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('division');
            }
        });

        // 2. Jika tipe kolom 'role' di MySQL sebelumnya ENUM dan ingin diubah ke VARCHAR tanpa error doctrine/dbal
        if (Schema::hasColumn('users', 'role')) {
            $driver = DB::connection()->getDriverName();
            if ($driver === 'mysql' || $driver === 'mariadb') {
                DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(30) NOT NULL DEFAULT 'user'");
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'division')) {
                $columnsToDrop[] = 'division';
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $columnsToDrop[] = 'is_active';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};