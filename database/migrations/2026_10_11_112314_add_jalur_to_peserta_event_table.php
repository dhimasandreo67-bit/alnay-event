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
            $table->string('jalur')->default('reguler')->after('status'); // 'reguler' atau 'langsung'
        });
    }
};
