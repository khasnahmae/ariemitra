<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');

            $table->integer('rating')->default(5); // Rating 1-5
            $table->text('pesan_testimoni');
            $table->string('jabatan_profesi')->nullable(); // Misal: Ketua Panitia / Kepala Sekolah / Guru
            $table->boolean('is_published')->default(true); // Status tampil di frontend

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
