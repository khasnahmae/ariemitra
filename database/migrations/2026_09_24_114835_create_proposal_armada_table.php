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
        Schema::create('proposal_armada', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->foreignId('armada_id')->constrained('armada')->onDelete('restrict');
            $table->integer('jumlah_unit')->default(1);
            $table->decimal('sewa_snapshot', 12, 2)->comment('Harga sewa armada saat proposal dibuat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_armada');
    }
};
