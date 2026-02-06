<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin default
        Pengguna::create([
            'nama' => 'Romi Yusnandar',
            'email' => 'romi@pupuk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'alamat' => 'Jl. Admin No. 1',
            'kontak' => '081234567890',
            'status' => 'approved',
        ]);
    }
}
