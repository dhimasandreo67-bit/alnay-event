<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            // Kolom hasil_audisi: null, 'lolos', 'tidak_lolos'
            $table->string('hasil_audisi')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropColumn('hasil_audisi');
        });
    }
};