<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Cek dulu apakah kolomnya sudah ada atau belum
            if (!Schema::hasColumn('assets', 'panjang_km')) {
                $table->decimal('panjang_km', 8, 2)->default(0);
            }
            
            // Lakukan hal yang sama jika jumlah_tower juga ingin ditambahkan lewat migration
            if (!Schema::hasColumn('assets', 'jumlah_tower')) {
                $table->integer('jumlah_tower')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['panjang_km', 'jumlah_tower']);
        });
    }
};