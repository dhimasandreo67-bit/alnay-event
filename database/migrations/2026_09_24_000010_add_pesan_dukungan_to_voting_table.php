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
        Schema::table('voting', function (Blueprint $table) {
            $table->text('pesan_dukungan')->nullable()->after('status_midtrans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            $table->dropColumn('pesan_dukungan');
        });
    }
};
