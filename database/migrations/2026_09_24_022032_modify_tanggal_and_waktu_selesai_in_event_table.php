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
            Schema::table('event', function (Blueprint $table) {
                $table->date('tanggal_selesai')->nullable()->change();
                $table->time('waktu_selesai')->nullable()->change();
            });
        }

    public function down(): void
        {
            Schema::table('event', function (Blueprint $table) {
                $table->date('tanggal_selesai')->nullable(false)->change();
                $table->time('waktu_selesai')->nullable(false)->change();
            });
        }
};
