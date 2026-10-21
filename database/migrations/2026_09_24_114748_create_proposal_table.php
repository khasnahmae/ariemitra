<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('paket_wisata_id')->constrained('paket_wisata')->onDelete('cascade');
            $table->string('nama_sub_paket', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->integer('jumlah_hari')->default(1);
            $table->integer('jumlah_malam')->default(0);
            $table->enum('is_menginap', ['ya', 'tidak'])->default('tidak');

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

    public function down(): void
    {
        Schema::dropIfExists('proposal');
    }
};
