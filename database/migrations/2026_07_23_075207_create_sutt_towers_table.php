<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sutt_towers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sutt_line_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('tower_number'); // dari suffix "T00xx" di Functloc, dipakai buat urutan sepanjang jalur
            $table->string('functloc')->nullable();
            $table->string('name')->nullable();
            $table->decimal('latitude', 12, 7);
            $table->decimal('longitude', 12, 7);
            $table->timestamps();

            $table->unique(['sutt_line_id', 'tower_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sutt_towers');
    }
};