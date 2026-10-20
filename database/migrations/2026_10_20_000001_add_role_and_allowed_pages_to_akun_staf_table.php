<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akun_staf', function (Blueprint $table) {
            // 'super_admin' = akses penuh ke semua halaman & semua event
            // 'admin'       = akses dibatasi sesuai kolom allowed_pages + tabel akun_staf_event
            $table->string('role', 20)->default('admin')->after('email');

            // Daftar slug halaman yang boleh diakses admin ini, contoh: ["dashboard","form-builder"]
            // Diabaikan total kalau role = super_admin
            $table->json('allowed_pages')->nullable()->after('role');
        });

        // Akun yang sudah ada sekarang (satu-satunya admin saat ini, MAS ERDEN) dijadikan Super Admin,
        // supaya tidak ada yang langsung terkunci keluar dari sistem setelah migration ini jalan.
        DB::table('akun_staf')->update(['role' => 'super_admin']);
    }

    public function down(): void
    {
        Schema::table('akun_staf', function (Blueprint $table) {
            $table->dropColumn(['role', 'allowed_pages']);
        });
    }
};
