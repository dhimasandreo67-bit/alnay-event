<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akun_staf', function (Blueprint $table) {
            if (!Schema::hasColumn('akun_staf', 'last_active_event_id')) {
                $table->unsignedBigInteger('last_active_event_id')->nullable()->after('email');
                $table->foreign('last_active_event_id')->references('id')->on('event')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('akun_staf', function (Blueprint $table) {
            $table->dropForeign(['last_active_event_id']);
            $table->dropColumn('last_active_event_id');
        });
    }
};