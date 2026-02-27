<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Data pengguna & desa dijalankan terpisah
            // PenggunaSeeder::class,
            // AdminSeeder::class,
            // DesaSeeder::class,

            // Master data (kategori, supplier, nutrisi, pupuk, pupuk_nutrisi)
            MasterDataSeeder::class,
        ]);
    }
}
