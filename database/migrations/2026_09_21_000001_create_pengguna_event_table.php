<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna_event', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->unique();
            $table->string('nama');
            $table->string('email')->index();
            $table->string('foto_url', 500)->nullable();
            $table->dateTime('tanggal_daftar')->useCurrent();
            $table->dateTime('login_terakhir')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna_event');
    }
};
