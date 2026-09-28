<?php

namespace Database\Seeders;

use App\Models\AkunStaf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Milik main
        $this->call([
            AkunStafSeeder::class,
        ]);

        // Milik han: akun admin untuk dashboard Blade
        AkunStaf::updateOrCreate(['email' => 'admin@alnay.id'], [
            'nama' => 'MAS RADEN',
            'password_hash' => Hash::make('admin12345'),
        ]);
    }
}