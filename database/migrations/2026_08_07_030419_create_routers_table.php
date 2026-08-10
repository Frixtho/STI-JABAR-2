<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('routers', function (Blueprint $table) {
            $table->id();
            $table->string('id_aset')->unique();
            $table->date('tanggal_perolehan')->nullable();
            $table->string('status_kepemilikan')->nullable();

            $table->string('status_kondisi')->nullable();
            $table->string('status_operasional')->nullable();
            $table->string('tingkat_kritikalitas')->nullable();

            $table->string('klasifikasi_keamanan')->nullable();

            $table->string('lokasi_aset_saat_ini')->nullable();
            $table->string('kode_lokasi')->nullable();

            $table->string('pic_pencatat')->nullable();

            // Atribut spesifik Router
            $table->string('merk');
            $table->string('model');
            $table->string('serial_number');
            $table->string('mac_address')->nullable();
            $table->string('ip_address_wan')->nullable();
            $table->string('ip_address_lan')->nullable();
            $table->string('jumlah_port_wan')->nullable();
            $table->string('jumlah_port_lan')->nullable();
            $table->string('mendukung_vpn')->nullable();
            $table->string('protokol_routing')->nullable();
            $table->string('throughput')->nullable();
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
        Schema::dropIfExists('routers');
    }
};