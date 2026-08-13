<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wireless_lans', function (Blueprint $table) {
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
            
            // Atribut Spesifik Wireless LAN Controller
            $table->string('merk');
            $table->string('model');
            $table->string('bentuk_fisik')->nullable(); // appliance / virtual appliance/VM / cloud
            $table->string('serial_number')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('enkripsi')->nullable(); // Enkripsi yang didukung
            $table->string('versi_firmware')->nullable();
            $table->decimal('konsumsi_daya', 8, 2)->nullable(); // Konsumsi (Watt)
            $table->string('rack')->nullable();
            $table->date('masa_berlaku_garansi')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wireless_lans');
    }
};