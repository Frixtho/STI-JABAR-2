<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_points', function (Blueprint $table) {
            $table->id();
            // Atribut Umum
            $table->string('id_aset')->unique();
            $table->date('tanggal_perolehan');
            $table->string('status_kepemilikan');
            $table->string('keterangan_kepemilikan')->nullable();
            $table->string('status_kondisi');
            $table->string('status_operasional');
            $table->string('tingkat_kritikalitas');
            $table->string('klasifikasi_keamanan');
            $table->text('deskripsi_fungsi_aset')->nullable();
            $table->string('lokasi_aset_saat_ini');
            $table->string('kode_lokasi');
            $table->string('keterangan_lokasi')->nullable();
            $table->date('tanggal_pemeriksaan_terakhir')->nullable();
            $table->string('pic_pencatat');
            $table->string('bidang_pencatat_aset')->nullable();

            // Atribut Spesifik Access Point
            $table->string('merk');
            $table->string('model');
            $table->string('serial_number');
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('nama_ssid')->nullable();
            $table->string('frekuensi')->nullable();
            $table->string('menggunakan_poe')->nullable();
            $table->string('standar_wifi')->nullable();
            $table->string('enkripsi_wifi')->nullable();
            $table->string('versi_firmware')->nullable();
            $table->string('konsumsi_daya')->nullable();
            $table->string('rack')->nullable();
            $table->date('masa_berlaku_garansi')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('access_points');
    }
};