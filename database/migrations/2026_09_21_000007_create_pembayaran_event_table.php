<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta_event')->restrictOnDelete();
            $table->decimal('jumlah', 12, 2);
            $table->string('metode')->nullable();
            $table->string('midtrans_order_id')->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('status_midtrans')->default('pending');
            $table->dateTime('tanggal_bayar')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_event');
    }
};
