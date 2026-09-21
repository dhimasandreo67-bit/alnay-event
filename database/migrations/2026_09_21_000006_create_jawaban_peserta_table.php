<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_peserta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_id')->constrained('peserta_event')->restrictOnDelete();
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan_event')->restrictOnDelete();
            $table->text('jawaban')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_peserta');
    }
};
