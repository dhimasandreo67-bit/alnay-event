<?php

namespace Database\Seeders;

use App\Models\AkunStaf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        AkunStaf::updateOrCreate(['email' => 'admin@alnay.id'], [
            'nama' => 'MAS RADEN',
            'password_hash' => Hash::make('admin12345'),
        ]);
    }
}
