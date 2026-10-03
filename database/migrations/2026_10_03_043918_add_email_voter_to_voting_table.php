<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            if (!Schema::hasColumn('voting', 'email_voter')) {
                $table->string('email_voter', 255)
                      ->nullable()
                      ->after('nama_voter')
                      ->comment('Email voter untuk vote manual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('voting', function (Blueprint $table) {
            $table->dropColumn('email_voter');
        });
    }
};