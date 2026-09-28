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
        Schema::table('voting', function (Blueprint $table) {
            $table->string('nama_pemilih')->nullable()->after('user_id');
            $table->string('email_pemilih')->nullable()->after('nama_pemilih');
            $table->string('metode_pembayaran')->nullable()->after('midtrans_order_id');
            $table->string('va_number')->nullable()->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            $table->dropColumn(['nama_pemilih', 'email_pemilih', 'metode_pembayaran', 'va_number']);
        });
    }
};
