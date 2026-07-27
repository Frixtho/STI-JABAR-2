<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (! Schema::hasColumn('assets', 'gi_awal_id')) {
                $table->foreignId('gi_awal_id')->nullable()->after('functloc')->constrained('units')->nullOnDelete();
            }
            if (! Schema::hasColumn('assets', 'gi_akhir_id')) {
                $table->foreignId('gi_akhir_id')->nullable()->after('gi_awal_id')->constrained('units')->nullOnDelete();
            }
            if (! Schema::hasColumn('assets', 'latitude')) {
                $table->decimal('latitude', 12, 7)->nullable()->after('gi_akhir_id');
            }
            if (! Schema::hasColumn('assets', 'longitude')) {
                $table->decimal('longitude', 12, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            foreach (['gi_awal_id', 'gi_akhir_id', 'latitude', 'longitude'] as $col) {
                if (Schema::hasColumn('assets', $col)) {
                    // drop foreign key dulu kalau ada, baru kolomnya
                    if (in_array($col, ['gi_awal_id', 'gi_akhir_id'])) {
                        $table->dropConstrainedForeignId($col);
                    } else {
                        $table->dropColumn($col);
                    }
                }
            }
        });
    }
};