<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('metode');
            $table->string('qr_string')->nullable()->after('payment_method');
            $table->string('va_number')->nullable()->after('qr_string');
            $table->string('qr_url')->nullable()->after('va_number');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'qr_string', 'va_number', 'qr_url']);
        });
    }
};