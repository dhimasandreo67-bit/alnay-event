<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->json('galeri_foto')->nullable()->after('tanggal_daftar');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropColumn('galeri_foto');
        });
    }
};