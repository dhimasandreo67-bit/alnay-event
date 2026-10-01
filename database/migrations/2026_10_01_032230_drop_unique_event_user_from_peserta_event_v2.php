<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            // 1. Drop FK dulu
            $table->dropForeign('peserta_event_event_id_foreign');
            $table->dropForeign('peserta_event_user_id_foreign');

            // 2. Drop unique composite index
            $table->dropUnique('peserta_event_event_user_unik');

            // 3. Bikin index biasa (bukan unique)
            $table->index('event_id');
            $table->index('user_id');

            // 4. Bikin FK ulang
            $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('pengguna_event')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropForeign('peserta_event_event_id_foreign');
            $table->dropForeign('peserta_event_user_id_foreign');
            $table->dropIndex(['event_id']);
            $table->dropIndex(['user_id']);

            $table->unique(['event_id', 'user_id'], 'peserta_event_event_user_unik');

            $table->foreign('event_id')->references('id')->on('event')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('pengguna_event')->onDelete('cascade');
        });
    }
};