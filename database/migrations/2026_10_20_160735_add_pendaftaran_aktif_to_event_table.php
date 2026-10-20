<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            // Menambahkan kolom boolean, default 0 (false)
            // Sesuaikan 'after' dengan nama kolom terakhir di tabel event kamu
            $table->boolean('pendaftaran_aktif')->default(0)->after('waktu_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('pendaftaran_aktif');
        });
    }
};