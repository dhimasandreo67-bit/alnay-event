<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->boolean('tampil_di_publik')->default(true)->after('total_vote');
        });
    }

    public function down(): void
    {
        Schema::table('peserta_event', function (Blueprint $table) {
            $table->dropColumn('tampil_di_publik');
        });
    }
};