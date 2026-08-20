<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_category_fields', function (Blueprint $table) {
            $table->id();
            // Relasi ke kategori aset
            $table->foreignId('asset_category_id')->constrained('asset_categories')->cascadeOnDelete();

            $table->string('name'); // Label yang muncul di layar, cth: "IP Address", "Plat Nomor"
            $table->string('field_key'); // Nama variabel (tanpa spasi), cth: "ip_address", "plat_nomor"
            $table->string('field_type')->default('text'); // Tipe input: text, number, date, select
            $table->json('options')->nullable(); // Jika tipenya 'select', ini menyimpan daftar pilihannya

            $table->boolean('is_required')->default(false); // Apakah field ini wajib diisi?
            $table->boolean('show_in_table')->default(true); // Apakah data ini dimunculkan di kolom tabel depan?
            $table->integer('order_position')->default(0); // Urutan tampil di form

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_category_fields');
    }
};