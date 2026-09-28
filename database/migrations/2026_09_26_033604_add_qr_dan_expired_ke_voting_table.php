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
            $table->text('qr_url')->nullable()->after('va_number');
            $table->timestamp('expired_at')->nullable()->after('qr_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            $table->dropColumn(['qr_url', 'expired_at']);
        });
    }
};
