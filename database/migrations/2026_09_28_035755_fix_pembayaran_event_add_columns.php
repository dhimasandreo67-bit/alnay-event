<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            if (!Schema::hasColumn('pembayaran_event', 'tarif_dasar')) {
                $table->integer('tarif_dasar')->nullable()->after('jumlah');
            }
            if (!Schema::hasColumn('pembayaran_event', 'kode_unik')) {
                $table->string('kode_unik', 10)->nullable()->after('tarif_dasar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran_event', function (Blueprint $table) {
            $table->dropColumn(['tarif_dasar', 'kode_unik']);
        });
    }
};