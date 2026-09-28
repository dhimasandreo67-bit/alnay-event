<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Isi asli han: softDeletes di pertanyaan_event (nama file menyesatkan).
    // Diberi pengecekan karena migration soft_deletes di bawahnya menambah kolom yang sama.
    public function up(): void
    {
        if (! Schema::hasColumn('pertanyaan_event', 'deleted_at')) {
            Schema::table('pertanyaan_event', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('pertanyaan_event', 'deleted_at')) {
            Schema::table('pertanyaan_event', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};