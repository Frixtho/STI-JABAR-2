<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sutt_lines', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // dari kolom "Induk" baris TOWER, misal "TRS 70kV UJUNGBERUNG-SUMEDANG"
            $table->string('voltage')->nullable(); // misal "70kV", diparsing dari name
            $table->string('code_pair')->nullable(); // misal "254.229", dua kode segmen Functloc endpoint-nya
            $table->foreignId('gi_start_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('gi_end_id')->nullable()->constrained('units')->nullOnDelete();
            $table->timestamps();

            $table->unique(['name', 'code_pair']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sutt_lines');
    }
};