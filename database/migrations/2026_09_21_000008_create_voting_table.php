<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event')->restrictOnDelete();
            $table->foreignId('peserta_id')->constrained('peserta_event')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('pengguna_event')->restrictOnDelete();
            $table->string('nama_tampilan');
            $table->unsignedInteger('jumlah_vote');
            $table->decimal('harga_per_vote', 12, 2); // snapshot dari event saat transaksi
            $table->decimal('total_bayar', 12, 2);
            $table->string('midtrans_order_id')->unique();
            $table->string('status_midtrans')->default('pending');
            $table->dateTime('tanggal_vote')->useCurrent();
            $table->index(['peserta_id', 'status_midtrans']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting');
    }
};
