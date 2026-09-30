<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event', function (Blueprint $table) {
            if (Schema::hasColumn('event', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable()->change();
            }
            if (Schema::hasColumn('event', 'waktu_selesai')) {
                $table->time('waktu_selesai')->nullable()->change();
            }
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