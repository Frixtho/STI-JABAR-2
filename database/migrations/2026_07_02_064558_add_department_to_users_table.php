<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // Pastikan Facade Schema ini yang di-import

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ganti 'Route::table' menjadi 'Schema::table'
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->unique()->nullable()->after('email');
            $table->string('department')->nullable()->after('nip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ganti 'Route::table' menjadi 'Schema::table'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'department']);
        });
    }
};