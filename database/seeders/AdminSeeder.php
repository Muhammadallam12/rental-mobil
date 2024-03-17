<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@mail.com',
            'password' => bcrypt('admin123'), // Ganti dengan kata sandi yang Anda inginkan
            'alamat' => 'gunungkidul',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@example.com',
            'password' => bcrypt('admin1212'), // Ganti dengan kata sandi yang Anda inginkan
            'role' => 'admin',
            'alamat' => 'gununglor',
        ]);
    }
}
