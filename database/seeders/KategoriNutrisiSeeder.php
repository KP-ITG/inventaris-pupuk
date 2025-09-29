<?php

namespace Database\Seeders;

use App\Models\KategoriPupuk;
use App\Models\Nutrisi;
use Illuminate\Database\Seeder;

class KategoriNutrisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Kategori Pupuk
        $kategoris = [
            ['nama_kategori' => 'Pupuk Organik', 'deskripsi' => 'Pupuk yang berasal dari bahan-bahan organik alami'],
            ['nama_kategori' => 'Pupuk Anorganik', 'deskripsi' => 'Pupuk buatan atau sintetis dengan kandungan nutrisi terukur'],
            ['nama_kategori' => 'Pupuk Majemuk', 'deskripsi' => 'Pupuk yang mengandung lebih dari satu unsur hara'],
            ['nama_kategori' => 'Pupuk Tunggal', 'deskripsi' => 'Pupuk yang mengandung satu jenis unsur hara utama'],
        ];

        foreach ($kategoris as $kategori) {
            KategoriPupuk::create($kategori);
        }

        // Seed Nutrisi
        $nutrisis = [
            ['nama_nutrisi' => 'Nitrogen', 'satuan' => '%', 'formula_kimia' => 'N', 'deskripsi_nutrisi' => 'Unsur hara makro untuk pertumbuhan vegetatif tanaman'],
            ['nama_nutrisi' => 'Fosfor', 'satuan' => '%', 'formula_kimia' => 'P2O5', 'deskripsi_nutrisi' => 'Unsur hara makro untuk pembentukan akar dan bunga'],
            ['nama_nutrisi' => 'Kalium', 'satuan' => '%', 'formula_kimia' => 'K2O', 'deskripsi_nutrisi' => 'Unsur hara makro untuk ketahanan tanaman'],
            ['nama_nutrisi' => 'Magnesium', 'satuan' => '%', 'formula_kimia' => 'MgO', 'deskripsi_nutrisi' => 'Unsur hara sekunder untuk pembentukan klorofil'],
            ['nama_nutrisi' => 'Sulfur', 'satuan' => '%', 'formula_kimia' => 'S', 'deskripsi_nutrisi' => 'Unsur hara sekunder untuk sintesis protein'],
            ['nama_nutrisi' => 'Kalsium', 'satuan' => '%', 'formula_kimia' => 'CaO', 'deskripsi_nutrisi' => 'Unsur hara sekunder untuk struktur dinding sel'],
            ['nama_nutrisi' => 'Besi', 'satuan' => 'ppm', 'formula_kimia' => 'Fe', 'deskripsi_nutrisi' => 'Unsur hara mikro untuk fotosintesis'],
            ['nama_nutrisi' => 'Zinc', 'satuan' => 'ppm', 'formula_kimia' => 'Zn', 'deskripsi_nutrisi' => 'Unsur hara mikro untuk enzim tanaman'],
        ];

        foreach ($nutrisis as $nutrisi) {
            Nutrisi::create($nutrisi);
        }
    }
}
