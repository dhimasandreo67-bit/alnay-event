<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom hanya kalau belum ada (aman kalau migration ini pernah jalan sebagian)
        Schema::table('event', function (Blueprint $table) {
            if (! Schema::hasColumn('event', 'deskripsi')) {
                $table->text('deskripsi')->nullable();
            }
            if (! Schema::hasColumn('event', 'waktu_mulai')) {
                $table->time('waktu_mulai')->nullable();
            }
            if (! Schema::hasColumn('event', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable();
            }
            if (! Schema::hasColumn('event', 'lokasi')) {
                $table->string('lokasi')->nullable();
            }
            if (! Schema::hasColumn('event', 'batas_pendaftaran_tanggal')) {
                $table->date('batas_pendaftaran_tanggal')->nullable();
            }
            if (! Schema::hasColumn('event', 'batas_pendaftaran_waktu')) {
                $table->time('batas_pendaftaran_waktu')->nullable();
            }
            if (! Schema::hasColumn('event', 'kuota_peserta')) {
                $table->unsignedInteger('kuota_peserta')->nullable();
            }
        });

        // Kolom lama dibuat boleh null
        Schema::table('event', function (Blueprint $table) {
            $table->date('tanggal_selesai')->nullable()->change();
            $table->time('waktu_selesai')->nullable()->change();
        });
    }

    public function down(): void
    {
        $kolom = array_filter([
            'deskripsi',
            'waktu_mulai',
            'waktu_selesai',
            'lokasi',
            'batas_pendaftaran_tanggal',
            'batas_pendaftaran_waktu',
            'kuota_peserta',
        ], fn ($k) => Schema::hasColumn('event', $k));

        if ($kolom) {
            Schema::table('event', function (Blueprint $table) use ($kolom) {
                $table->dropColumn($kolom);
            });
        }
    }
};