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
        Schema::create('proposal_tol', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');
            $table->foreignId('rute_tol_id')->constrained('rute_tol')->onDelete('restrict');
            $table->enum('tipe_perjalanan', ['pp', 'oneway'])->default('pp');
            $table->decimal('tarif_snapshot', 12, 2)->comment('Tarif tol saat proposal dibuat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_tol');
    }
};
