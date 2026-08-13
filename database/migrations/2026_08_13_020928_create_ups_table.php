<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ups', function (Blueprint $table) {
            $table->id();
            
            // Atribut Umum
            $table->string('id_aset')->unique();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('status_kepemilikan')->nullable();
            $table->string('keterangan_status_kepemilikan')->nullable();
            $table->string('status_kondisi')->default('baik');
            $table->string('status_operasional')->default('aktif');
            $table->string('tingkat_kritikalitas')->default('normal');
            $table->string('klasifikasi_keamanan')->default('internal');
            $table->text('deskripsi_tujuan')->nullable();
            $table->string('lokasi_aset_saat_ini');
            $table->string('keterangan_lokasi_aset')->nullable();
            $table->date('tanggal_pemeriksaan_terakhir')->nullable();
            $table->string('pic_pencatat')->nullable();
            $table->string('bidang_pencatat_aset')->nullable();
            
            // Atribut Spesifik UPS
            $table->string('merk');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->string('tipe_kimia')->nullable();
            $table->string('ip_address')->nullable();
            $table->integer('jumlah_baterai')->nullable();
            $table->decimal('kapasitas_total', 10, 2)->nullable(); // Kapasitas (kWh)
            $table->text('spesifikasi')->nullable();
            $table->decimal('konsumsi_daya', 8, 2)->nullable(); // Konsumsi (Watt)
            $table->date('masa_berlaku_garansi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ups');
    }
};