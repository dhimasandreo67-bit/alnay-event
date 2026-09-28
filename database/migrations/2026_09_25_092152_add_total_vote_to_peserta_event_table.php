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
            $table->unsignedInteger('total_vote')->default(0)->after('galeri_foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropColumn('total_vote');
        });
    }
};
