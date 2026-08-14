<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Detail (Isi Tower)
        Schema::create('sutt_towers', function (Blueprint $table) {
            $table->id();
            
            // Relasi yang BENAR sesuai dengan permintaan Controller (sutt_line_id)
            $table->foreignId('sutt_line_id')->constrained('sutt_lines')->cascadeOnDelete(); 
            
            $table->string('tower_number')->nullable();
            $table->string('functloc')->nullable();
            $table->string('name')->nullable();
            $table->string('nama_tower')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->decimal('jarak_antar_tower', 12, 2)->nullable(); // Dalam meter
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sutt_towers');
    }
};