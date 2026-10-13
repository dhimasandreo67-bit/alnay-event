<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->boolean('sudah_audisi')->default(false)->after('status');
            $table->timestamp('tanggal_audisi')->nullable()->after('sudah_audisi');
            $table->enum('status_registrasi', ['belum', 'dp', 'lunas'])
                  ->default('belum')->after('tanggal_audisi');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropColumn(['sudah_audisi', 'tanggal_audisi', 'status_registrasi']);
        });
    }
};