<?php

namespace Database\Seeders;

use App\Models\Nutrisi;
use Illuminate\Database\Seeder;

class NutrisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing nutrisi
        Nutrisi::whereNotNull('id')->delete();

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
