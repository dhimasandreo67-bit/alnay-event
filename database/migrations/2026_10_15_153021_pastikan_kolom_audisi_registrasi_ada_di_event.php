<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            if (!Schema::hasColumn('event', 'tanggal_audisi')) {
                $table->date('tanggal_audisi')->nullable();
            }
            if (!Schema::hasColumn('event', 'audisi_aktif')) {
                $table->boolean('audisi_aktif')->default(false);
            }
            if (!Schema::hasColumn('event', 'audisi_lokasi')) {
                $table->string('audisi_lokasi')->nullable();
            }
            if (!Schema::hasColumn('event', 'registrasi_aktif')) {
                $table->boolean('registrasi_aktif')->default(true);
            }
        });
    }

    public function down(): void
    {
        // sengaja kosong: migrasi ini hanya pengaman
    }
};