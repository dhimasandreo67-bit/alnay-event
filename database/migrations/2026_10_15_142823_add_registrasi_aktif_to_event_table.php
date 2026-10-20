<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->boolean('registrasi_aktif')->default(true)->after('audisi_aktif');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn('registrasi_aktif');
        });
    }
};