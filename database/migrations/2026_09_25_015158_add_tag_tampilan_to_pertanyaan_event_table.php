<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pertanyaan_event', function (Blueprint $table) {
            $table->string('tag_tampilan')->nullable()->after('urutan');
        });
    }

    public function down(): void
    {
        Schema::table('pertanyaan_event', function (Blueprint $table) {
            $table->dropColumn('tag_tampilan');
        });
    }
};