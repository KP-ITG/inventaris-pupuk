<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat admin default
        Pengguna::create([
            'nama' => 'Administrator',
            'email' => 'admin@pupuk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'alamat' => 'Jl. Admin No. 1',
            'kontak' => '081234567890',
            'status' => 'approved',
        ]);

        // Buat distributor contoh
        Pengguna::create([
            'nama' => 'Distributor Contoh',
            'email' => 'distributor@pupuk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'distributor',
            'alamat' => 'Jl. Distributor No. 2',
            'kontak' => '081987654321',
            'status' => 'approved',
        ]);
    }
}
