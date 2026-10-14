<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->date('tanggal_audisi')->nullable()->after('tanggal_selesai');
            $table->boolean('audisi_aktif')->default(false)->after('tanggal_audisi');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['tanggal_audisi', 'audisi_aktif']);
        });
    }
};