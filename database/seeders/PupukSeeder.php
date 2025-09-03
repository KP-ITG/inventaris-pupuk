<?php

namespace Database\Seeders;

use App\Models\Pupuk;
use App\Models\KategoriPupuk;
use Illuminate\Database\Seeder;

class PupukSeeder extends Seeder
{
    public function run(): void
    {
        $categories = KategoriPupuk::all();

        $pupuks = [
            [
                'nama_pupuk' => 'Urea 46%',
                'kategori_id' => $categories->where('nama_kategori', 'Pupuk Urea')->first()->id,
                'harga_jual' => 2500,
                'deskripsi' => 'Pupuk urea dengan kandungan nitrogen 46%'
            ],
            [
                'nama_pupuk' => 'NPK 15-15-15',
                'kategori_id' => $categories->where('nama_kategori', 'Pupuk NPK')->first()->id,
                'harga_jual' => 3200,
                'deskripsi' => 'Pupuk NPK dengan komposisi seimbang'
            ],
            [
                'nama_pupuk' => 'TSP (Triple Super Phosphate)',
                'kategori_id' => $categories->where('nama_kategori', 'Pupuk Anorganik')->first()->id,
                'harga_jual' => 2800,
                'deskripsi' => 'Pupuk fosfor untuk pembungaan dan pembuahan'
            ],
            [
                'nama_pupuk' => 'Kompos Organik',
                'kategori_id' => $categories->where('nama_kategori', 'Pupuk Organik')->first()->id,
                'harga_jual' => 1500,
                'deskripsi' => 'Pupuk organik dari hasil fermentasi sampah organik'
            ],
            [
                'nama_pupuk' => 'NPK 16-16-16',
                'kategori_id' => $categories->where('nama_kategori', 'Pupuk NPK')->first()->id,
                'harga_jual' => 3500,
                'deskripsi' => 'Pupuk NPK dengan kandungan tinggi'
            ],
        ];

        foreach ($pupuks as $pupuk) {
            Pupuk::create($pupuk);
        }
    }
}
