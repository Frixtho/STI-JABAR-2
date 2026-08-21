<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Menggunakan pengecekan hasColumn agar aman jika kolom sudah terlanjur ada
            if (!Schema::hasColumn('assets', 'asset_id')) {
                $table->string('asset_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('assets', 'unit_name')) {
                $table->string('unit_name')->nullable()->after('category');
            }
            if (!Schema::hasColumn('assets', 'acquisition_date')) {
                $table->date('acquisition_date')->nullable();
            }
            if (!Schema::hasColumn('assets', 'ownership_status')) {
                $table->string('ownership_status')->nullable();
            }
            if (!Schema::hasColumn('assets', 'ownership_desc')) {
                $table->string('ownership_desc')->nullable();
            }
            if (!Schema::hasColumn('assets', 'condition_status')) {
                $table->string('condition_status')->nullable();
            }
            if (!Schema::hasColumn('assets', 'operational_status')) {
                $table->string('operational_status')->nullable();
            }
            if (!Schema::hasColumn('assets', 'criticality_level')) {
                $table->string('criticality_level')->nullable();
            }
            if (!Schema::hasColumn('assets', 'security_classification')) {
                $table->string('security_classification')->nullable();
            }
            if (!Schema::hasColumn('assets', 'location_desc')) {
                $table->text('location_desc')->nullable();
            }
            if (!Schema::hasColumn('assets', 'last_maintenance_date')) {
                $table->date('last_maintenance_date')->nullable();
            }
            if (!Schema::hasColumn('assets', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('assets', 'pic')) {
                $table->string('pic')->nullable();
            }
            if (!Schema::hasColumn('assets', 'pic_department')) {
                $table->string('pic_department')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn([
                'asset_id', 'unit_name', 'acquisition_date', 'ownership_status', 'ownership_desc',
                'condition_status', 'operational_status', 'criticality_level', 'security_classification',
                'location_desc', 'last_maintenance_date', 'description', 'pic', 'pic_department'
            ]);
        });
    }
};