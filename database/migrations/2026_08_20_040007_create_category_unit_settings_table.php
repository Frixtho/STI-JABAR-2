<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('category_unit_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_category_id')->constrained('asset_categories')->cascadeOnDelete();
            $table->string('unit_name'); // Nama departemen/unit dari tabel user
            $table->boolean('is_active'); // Status paksa (true/false)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_unit_settings');
    }
};
