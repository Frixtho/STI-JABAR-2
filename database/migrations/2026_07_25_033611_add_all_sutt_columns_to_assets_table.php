<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (! Schema::hasColumn('assets', 'tegangan')) {
                $table->string('tegangan')->nullable();
            }
            if (! Schema::hasColumn('assets', 'gi_awal_id')) {
                $table->unsignedBigInteger('gi_awal_id')->nullable();
            }
            if (! Schema::hasColumn('assets', 'gi_akhir_id')) {
                $table->unsignedBigInteger('gi_akhir_id')->nullable();
            }
            if (! Schema::hasColumn('assets', 'jumlah_tower')) {
                $table->integer('jumlah_tower')->nullable();
            }
            if (! Schema::hasColumn('assets', 'panjang_km')) {
                $table->decimal('panjang_km', 8, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            foreach (['tegangan', 'gi_awal_id', 'gi_akhir_id', 'jumlah_tower', 'panjang_km'] as $col) {
                if (Schema::hasColumn('assets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};