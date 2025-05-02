<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CompanySetting;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    User::factory()->create([
        'name' => 'Admin',
        'email' => 'admin.admin@gmail.com',
        'password' => bcrypt('12345678'),
        'role' => 'admin',
    ]);

    CompanySetting::factory()->create([
        'name' => 'PT Megah Profile',
        'description' => 'Membangun semua bidang usaha yang ada di dunia ini',
        'address' => 'Jl. Raya No. 8 kota Serang Indonesia 12345 ',
        'phone' => '+62 123 4567 890',
        'value' => 'Berani Berubah untuk Maju Bersama',

    ]);

    }
}
