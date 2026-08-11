<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('firewalls', function (Blueprint $table) {
            $table->id();
            // Atribut Umum
            $table->string('id_aset')->unique(); // 21 digit format
            $table->date('tanggal_mulai_aktif')->nullable();
            $table->string('status_kepemilikan')->nullable();
            $table->text('keterangan_status_kepemilikan')->nullable();
            $table->string('status_kondisi_aset')->nullable();
            $table->string('status_operasional_aset')->nullable();
            $table->string('tingkat_kritikalitas_aset')->nullable();
            $table->string('klasifikasi_keamanan_aset')->nullable();
            $table->text('deskripsi_tujuan_peran_fungsi')->nullable();
            $table->string('lokasi_aset_saat_ini')->nullable();
            $table->text('keterangan_lokasi_aset')->nullable();
            $table->date('tanggal_pemeriksaan_terakhir')->nullable();
            $table->string('pic_pencatat')->nullable();
            $table->string('bidang_pencatat_aset')->nullable();

            // Atribut Spesifik Firewall
            $table->string('merk');
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('segmen_number')->nullable();
            $table->string('segmen_tujuan')->nullable();
            $table->string('versi_firmware_os')->nullable();
            $table->decimal('konsumsi_daya', 8, 2)->nullable(); // dalam satuan Watt
            $table->string('rack')->nullable();
            $table->date('masa_berlaku_garansi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firewalls');
    }
};