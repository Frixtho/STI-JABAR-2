<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id'); // Atau foreign key ke tabel asset/tower
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang mengubah
            $table->string('action'); // Contoh: 'Created', 'Updated', 'Deleted'
            $table->text('description'); // Detail perubahan (misal: "Mengubah status dari X ke Y")
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_histories');
    }
};