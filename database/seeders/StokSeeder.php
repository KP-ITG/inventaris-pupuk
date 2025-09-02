<?php

namespace Database\Seeders;

use App\Models\Stok;
use App\Models\Pupuk;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $pupuks = Pupuk::all();
        $distributors = Pengguna::where('role', 'distributor')->where('status', 'approved')->get();

        foreach ($distributors as $distributor) {
            foreach ($pupuks->take(3) as $index => $pupuk) {
                $jumlah = [100, 250, 500, 50, 25][$index % 5];
                
                Stok::create([
                    'pupuk_id' => $pupuk->id,
                    'pengguna_id' => $distributor->id,
                    'jumlah_stok' => $jumlah,
                ]);
            }
        }
    }
}
