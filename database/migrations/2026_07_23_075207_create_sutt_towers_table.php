<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama (Daftar File)
        Schema::create('tower_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Tabel Detail (Isi Tower)
        Schema::create('towers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tower_file_id')->constrained('tower_files')->cascadeOnDelete(); // Terhubung ke file
            $table->string('tower_number')->nullable();
            $table->string('functloc')->nullable();
            $table->string('nama_tower')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->decimal('jarak_antar_tower', 12, 2)->nullable(); // Dalam meter
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('towers');
        Schema::dropIfExists('tower_files');
    }
};