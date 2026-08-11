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
            
            // Atribut Umum
            $table->string('id_aset')->unique();
            $table->date('tanggal_mulai_aktif')->nullable();
            $table->string('status_kepemilikan')->nullable();
            $table->string('keterangan_status_kepemilikan')->nullable();
            $table->string('status_kondisi')->nullable();
            $table->string('status_operasional')->nullable();
            $table->string('tingkat_kritikalitas')->nullable();
            $table->string('klasifikasi_keamanan')->nullable();
            $table->text('deskripsi_tujuan')->nullable();
            $table->string('lokasi_aset_saat_ini')->nullable();
            $table->string('keterangan_lokasi')->nullable();
            $table->date('tanggal_pemeriksaan_terakhir')->nullable();
            $table->string('pic_pencatat')->nullable();
            $table->string('bidang_pencatat_aset')->nullable();

            // Atribut Spesifik Router
            $table->string('merk');
            $table->string('model');
            $table->string('serial_number');
            $table->string('mac_address')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('jumlah_kecepatan_jenis_port')->nullable();
            $table->string('protocol_disupport')->nullable();
            $table->string('versi_firmware_os')->nullable();
            $table->integer('konsumsi_daya')->nullable();
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