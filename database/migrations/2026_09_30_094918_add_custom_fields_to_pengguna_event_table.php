<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna_event', function (Blueprint $table) {
            $table->string('nama_custom')->nullable()->after('nama');
            $table->string('foto_custom')->nullable()->after('foto_url');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna_event', function (Blueprint $table) {
            $table->dropColumn(['nama_custom', 'foto_custom']);
        });
    }
};