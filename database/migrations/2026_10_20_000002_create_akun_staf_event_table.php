<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akun_staf_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_staf_id')->constrained('akun_staf')->cascadeOnDelete();

            // NOTE: nama tabel event di DB kamu "event" (singular, lihat screenshot phpMyAdmin).
            // Kalau model Event kamu ternyata pakai tabel "events", ganti 'event' di bawah jadi 'events'.
            $table->foreignId('event_id')->constrained('event')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['akun_staf_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_staf_event');
    }
};
