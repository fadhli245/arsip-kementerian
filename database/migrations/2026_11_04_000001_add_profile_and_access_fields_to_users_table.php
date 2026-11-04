<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('user')->change();
            $table->string('division')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('division');
        });
    }

    public function down(): void
    {
        DB::table('users')->whereNotIn('role', ['admin', 'user'])->update(['role' => 'user']);
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['division', 'is_active']);
            $table->enum('role', ['admin', 'user'])->default('user')->change();
        });
    }
};
