<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peserta_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('event')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('pengguna_event')->restrictOnDelete();
            $table->string('status')->default('menunggu_pembayaran'); // menunggu_pembayaran, diterima, ditolak
            $table->string('hasil_akhir')->nullable();
            $table->decimal('skor_akhir', 8, 2)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->dateTime('tanggal_daftar')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peserta_event');
    }
};
