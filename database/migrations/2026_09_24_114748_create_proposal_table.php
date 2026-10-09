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
        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('kode_proposal', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_klien', 150);
            $table->date('tanggal_proposal');
            $table->string('durasi', 50)->default('1 Hari');
            $table->integer('jumlah_peserta');
            $table->text('fasilitas')->nullable();
            $table->decimal('total_biaya_fixed', 12, 2)->default(0);
            $table->decimal('biaya_fixed_per_pax', 12, 2)->default(0);
            $table->decimal('biaya_var_per_pax', 12, 2)->default(0);
            $table->decimal('margin_per_pax', 12, 2)->default(0);
            $table->decimal('harga_akhir_per_pax', 12, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal');
    }
};
