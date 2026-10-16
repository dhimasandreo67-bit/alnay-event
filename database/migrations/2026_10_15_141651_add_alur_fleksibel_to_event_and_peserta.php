<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah 3 saklar di tabel event
        Schema::table('event', function (Blueprint $table) {
            $table->boolean('pendaftaran_aktif')->default(true)->after('status');
            $table->boolean('butuh_audisi')->default(true)->after('pendaftaran_aktif');
            $table->boolean('butuh_registrasi')->default(true)->after('butuh_audisi');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['pendaftaran_aktif', 'butuh_audisi', 'butuh_registrasi']);
        });
    }
};