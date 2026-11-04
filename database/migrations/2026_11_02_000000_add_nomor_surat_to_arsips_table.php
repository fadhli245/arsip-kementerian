<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('arsips', 'nomor_surat')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->string('nomor_surat', 100)->nullable()->after('judul');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('arsips', 'nomor_surat')) {
            Schema::table('arsips', function (Blueprint $table) {
                $table->dropColumn('nomor_surat');
            });
        }
    }
};
