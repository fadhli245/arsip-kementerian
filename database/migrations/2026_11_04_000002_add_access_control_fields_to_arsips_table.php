<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->string('classification', 30)->default('Internal');
            $table->json('allowed_divisions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('approval_status', 20)->default('Disetujui');
        });
    }

    public function down(): void
    {
        Schema::table('arsips', function (Blueprint $table) {
            $table->dropColumn(['classification', 'allowed_divisions', 'is_active', 'approval_status']);
        });
    }
};
