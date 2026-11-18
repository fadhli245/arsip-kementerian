<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->string('format', 20)->nullable();
            $table->unsignedBigInteger('ukuran_bytes')->nullable();
            $table->string('unit_pengunggah', 150)->nullable();
            $table->timestamp('tanggal_unggah')->nullable();
            $table->index(['kategori', 'tanggal_dokumen'], 'arsips_kategori_tanggal_index');
        });

        Schema::table('archive_requests', function (Blueprint $table) {
            $table->string('kategori', 100)->nullable();
            $table->string('referensi', 100)->nullable();
            $table->text('alasan_penolakan')->nullable();
            $table->index(['user_id', 'status', 'created_at'], 'archive_requests_user_status_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('archive_requests', function (Blueprint $table) {
            $table->dropIndex('archive_requests_user_status_created_index');
            $table->dropColumn(['kategori', 'referensi', 'alasan_penolakan']);
        });

        Schema::table('arsips', function (Blueprint $table) {
            $table->dropIndex('arsips_kategori_tanggal_index');
            $table->dropColumn(['format', 'ukuran_bytes', 'unit_pengunggah', 'tanggal_unggah']);
        });
    }
};
