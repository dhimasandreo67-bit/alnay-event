<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            $table->integer('tarif_dasar')->nullable()->after('jumlah');
            $table->string('kode_unik', 10)->nullable()->after('tarif_dasar');
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            $table->dropColumn(['tarif_dasar', 'kode_unik']);
        });
    }
};