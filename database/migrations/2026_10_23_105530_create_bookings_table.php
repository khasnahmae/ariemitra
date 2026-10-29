<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('no_invoice', 50)->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('proposal_id')->constrained('proposal')->onDelete('cascade');

            // Detail Pemesanan
            $table->string('nama_pemesan');
            $table->string('telepon_pemesan', 20);
            $table->text('alamat_penjemputan');
            $table->date('tanggal_keberangkatan');
            $table->time('jam_penjemputan')->nullable();
            $table->string('tujuan_wisata');

            // Keuangan & Pembayaran
            $table->decimal('biaya_sewa', 12, 2);
            $table->decimal('dp_1', 12, 2)->default(0);
            $table->decimal('dp_2', 12, 2)->default(0);
            $table->decimal('nominal_pending', 12, 2)->default(0); // Nominal yang baru di-submit client
            $table->decimal('sisa_pembayaran', 12, 2);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('metode_pembayaran', ['transfer', 'tunai'])->nullable();

            // Status & Notifikasi
            $table->enum('status', ['pending', 'sent', 'pending_verification', 'booked', 'lunas', 'cancelled'])->default('pending');
            $table->boolean('is_read_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
