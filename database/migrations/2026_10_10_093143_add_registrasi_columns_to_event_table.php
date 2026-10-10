<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->decimal('harga_registrasi', 12, 2)->nullable()->after('harga_per_vote');
            $table->decimal('harga_registrasi_dp', 12, 2)->nullable()->after('harga_registrasi');
        });
    }

    public function down(): void
    {
        Schema::table('event', function (Blueprint $table) {
            $table->dropColumn(['harga_registrasi', 'harga_registrasi_dp']);
        });
    }
};