<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->string('status_manual', 20)->default('otomatis')->after('mode_penilaian');
            $table->boolean('pendaftaran_dibuka')->default(false)->after('status_manual');
            $table->boolean('tampil_di_beranda')->default(true)->after('pendaftaran_dibuka');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['status_manual', 'pendaftaran_dibuka', 'tampil_di_beranda']);
        });
    }
};