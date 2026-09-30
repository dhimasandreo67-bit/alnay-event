<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            if (!Schema::hasColumn('event', 'pembukaan_pendaftaran_tanggal')) {
                $table->date('pembukaan_pendaftaran_tanggal')->nullable()->after('tanggal_selesai');
            }
            if (!Schema::hasColumn('event', 'pembukaan_pendaftaran_waktu')) {
                $table->time('pembukaan_pendaftaran_waktu')->nullable()->after('pembukaan_pendaftaran_tanggal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['pembukaan_pendaftaran_tanggal', 'pembukaan_pendaftaran_waktu']);
        });
    }
};