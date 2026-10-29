<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('nominal_konfirmasi', 12, 2)->default(0)->after('sisa_pembayaran');
            $table->boolean('is_read')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['nominal_konfirmasi', 'is_read']);
        });
    }
};
