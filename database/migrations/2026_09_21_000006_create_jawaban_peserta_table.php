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
            
            // Tambahkan onDelete('cascade') pada foreign key pertanyaan_id
            $table->foreignId('pertanyaan_id')
                ->constrained('pertanyaan_event')
                ->onDelete('cascade'); 

            $table->foreignId('peserta_id')->constrained('peserta')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_peserta');
    }
};
