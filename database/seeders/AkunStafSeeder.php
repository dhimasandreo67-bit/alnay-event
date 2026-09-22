<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunStafSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('akun_staf')->insert([
            'nama' => 'Admin Pertama',
            'email' => 'admin@alnay.id',
            'password_hash' => Hash::make('password123'),
            'dibuat_pada' => now(),
        ]);
    }
}
