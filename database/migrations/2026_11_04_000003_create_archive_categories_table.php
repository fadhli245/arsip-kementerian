<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archive_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('classification_code', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        foreach (['Surat Masuk', 'Surat Keluar', 'Surat Keputusan (SK)', 'Laporan Kegiatan'] as $name) {
            DB::table('archive_categories')->insert([
                'name' => $name,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('archive_categories');
    }
};
