<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
            Schema::table('event', function (Blueprint $table) {
                // Mengubah kolom yang sudah ada di DB agar bisa nullable
                $table->date('tanggal_selesai')->nullable()->change();

                // Menambahkan kolom baru atau mengubah kolom waktu_selesai
                $table->text('deskripsi')->nullable()->after('nama');
                $table->time('waktu_mulai')->nullable()->after('tanggal_mulai');
                $table->time('waktu_selesai')->nullable()->change(); // Diubah agar boleh null
                $table->string('lokasi')->nullable()->after('waktu_selesai');
                $table->date('batas_pendaftaran_tanggal')->nullable()->after('lokasi');
                $table->time('batas_pendaftaran_waktu')->nullable()->after('batas_pendaftaran_tanggal');
                $table->unsignedInteger('kuota_peserta')->nullable()->after('batas_pendaftaran_waktu');
            });
        }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn([
                'deskripsi',
                'waktu_mulai',
                'waktu_selesai',
                'lokasi',
                'batas_pendaftaran_tanggal',
                'batas_pendaftaran_waktu',
                'kuota_peserta',
            ]);
        });
    }
};
