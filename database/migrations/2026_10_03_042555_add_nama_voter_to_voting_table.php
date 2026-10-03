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
            // 1. Tambahkan kolom nama_voter (nullable, untuk vote manual)
            if (!Schema::hasColumn('voting', 'nama_voter')) {
                $table->string('nama_voter', 255)
                      ->nullable()
                      ->after('user_id')
                      ->comment('Nama voter untuk vote manual (jika user_id null)');
            }

            // 2. Ubah user_id menjadi nullable (untuk vote manual admin)
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            // Kembalikan user_id ke NOT NULL
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            
            // Hapus kolom nama_voter
            $table->dropColumn('nama_voter');
        });
    }
};