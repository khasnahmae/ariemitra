<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('armada', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('jenis_armada', 100);
            $table->integer('kapasitas');
            $table->decimal('harga_sewa', 12, 2)->default(0);
            $table->enum('is_include_solar', ['ya', 'tidak'])->default('ya');
            $table->timestamps();
            $table->unique(['jenis_armada', 'kapasitas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armada');
    }
};
