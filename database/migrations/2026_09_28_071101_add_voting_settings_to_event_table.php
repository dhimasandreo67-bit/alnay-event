<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->string('format_hasil_voting')->default('persentase'); // angka|persentase|keduanya
            $table->dateTime('voting_mulai')->nullable();
            $table->dateTime('voting_selesai')->nullable();
            $table->boolean('auto_freeze_voting')->default(true);
            $table->boolean('blackout_h2_voting')->default(true);

            // Informasional saja - lihat catatan di VotingSettingsController
            $table->json('metode_pembayaran')->nullable();
            $table->string('rekening_bank')->nullable();
            $table->string('rekening_nomor')->nullable();
            $table->string('rekening_atas_nama')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn([
                'format_hasil_voting', 'voting_mulai', 'voting_selesai',
                'auto_freeze_voting', 'blackout_h2_voting',
                'metode_pembayaran', 'rekening_bank', 'rekening_nomor', 'rekening_atas_nama',
            ]);
        });
    }
};