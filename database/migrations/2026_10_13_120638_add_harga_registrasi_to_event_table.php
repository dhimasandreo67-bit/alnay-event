<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            // Tambahkan jika belum ada
            if (!Schema::hasColumn('event', 'harga_registrasi')) {
                $table->decimal('harga_registrasi', 15, 2)->nullable()->after('biaya_pendaftaran');
            }
            if (!Schema::hasColumn('event', 'harga_registrasi_dp')) {
                $table->decimal('harga_registrasi_dp', 15, 2)->nullable()->after('harga_registrasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['harga_registrasi', 'harga_registrasi_dp']);
        });
    }
};