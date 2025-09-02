<?php

namespace Database\Seeders;

use App\Models\KategoriPupuk;
use Illuminate\Database\Seeder;

class KategoriPupukSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'nama_kategori' => 'Pupuk Organik',
                'deskripsi' => 'Pupuk yang berasal dari bahan-bahan organik'
            ],
            [
                'nama_kategori' => 'Pupuk Anorganik',
                'deskripsi' => 'Pupuk buatan yang mengandung unsur hara'
            ],
            [
                'nama_kategori' => 'Pupuk NPK',
                'deskripsi' => 'Pupuk majemuk yang mengandung Nitrogen, Fosfor, dan Kalium'
            ],
            [
                'nama_kategori' => 'Pupuk Urea',
                'deskripsi' => 'Pupuk nitrogen untuk pertumbuhan tanaman'
            ],
        ];

        foreach ($categories as $category) {
            KategoriPupuk::create($category);
        }
    }
}
