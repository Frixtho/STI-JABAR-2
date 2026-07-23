<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedTinyInteger('level'); // 1=UIT, 2=UPT, 3=ULTG, 4=GI
            $table->string('type'); // uit, upt, ultg, gi
            $table->foreignId('parent_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
        });
    }
 
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};