<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('banner')->nullable();
            $table->text('syarat_ketentuan')->nullable();
            $table->decimal('biaya_pendaftaran', 12, 2); // wajib > 0, divalidasi di aplikasi
            $table->decimal('harga_per_vote', 12, 2);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('mode_penilaian'); // voting / juri (usulan nilai)
            $table->boolean('voting_aktif')->default(false);
            $table->boolean('tampilkan_hasil_voting')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};
