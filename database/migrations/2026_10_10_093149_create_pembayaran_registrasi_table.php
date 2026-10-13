<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_registrasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta_event')->onDelete('cascade');
            $table->enum('tipe', ['dp1', 'dp2', 'lunas']); // gelombang
            $table->decimal('jumlah', 12, 2);
            $table->decimal('tarif_dasar', 12, 2)->nullable();
            $table->string('kode_unik', 10)->nullable();
            $table->string('metode')->nullable(); // qris, bri_va, dll
            $table->string('payment_method')->nullable(); // dari Midtrans
            $table->text('qr_string')->nullable();
            $table->string('va_number')->nullable();
            $table->text('qr_url')->nullable();
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('status_midtrans')->default('pending');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_registrasi');
    }
};