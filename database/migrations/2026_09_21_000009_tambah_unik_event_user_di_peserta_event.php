<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            // Satu user hanya boleh mendaftar sekali per event
            $table->unique(['event_id', 'user_id'], 'peserta_event_event_user_unik');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropUnique('peserta_event_event_user_unik');
        });
    }
};
