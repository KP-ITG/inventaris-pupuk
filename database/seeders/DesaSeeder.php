<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Desa;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $desaData = [
            [
                'nama_desa' => 'Sukamakmur',
                'kecamatan' => 'Bogor Timur',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Jl. Raya Sukamakmur No. 123, Bogor Timur',
                'kode_pos' => '16710',
                'nama_kepala_desa' => 'Budi Santoso',
                'no_telepon' => '021-8765432',
                'jumlah_penduduk' => 5420,
                'luas_wilayah' => 12.45,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Tani Jaya',
                'kecamatan' => 'Cileungsi',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Jl. Tani Jaya RT 02 RW 05, Cileungsi',
                'kode_pos' => '16820',
                'nama_kepala_desa' => 'Siti Rahma',
                'no_telepon' => '021-8234567',
                'jumlah_penduduk' => 3890,
                'luas_wilayah' => 8.75,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Sumber Rezeki',
                'kecamatan' => 'Dramaga',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Komplek Sumber Rezeki, Dramaga',
                'kode_pos' => '16680',
                'nama_kepala_desa' => 'Ahmad Yusuf',
                'no_telepon' => '021-7654321',
                'jumlah_penduduk' => 4210,
                'luas_wilayah' => 15.30,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Maju Bersama',
                'kecamatan' => 'Kemang',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Jl. Kemang Raya KM 5, Kemang',
                'kode_pos' => '16310',
                'nama_kepala_desa' => 'Rina Kartika',
                'no_telepon' => '021-5432167',
                'jumlah_penduduk' => 6750,
                'luas_wilayah' => 20.80,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Sukamaju',
                'kecamatan' => 'Parung',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Desa Sukamaju RT 01 RW 03, Parung',
                'kode_pos' => '16330',
                'nama_kepala_desa' => 'Indra Gunawan',
                'no_telepon' => '021-9876543',
                'jumlah_penduduk' => 4880,
                'luas_wilayah' => 11.90,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Harapan Jaya',
                'kecamatan' => 'Gunung Putri',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Jl. Harapan Jaya No. 89, Gunung Putri',
                'kode_pos' => '16965',
                'nama_kepala_desa' => 'Dewi Sartika',
                'no_telepon' => '021-6543210',
                'jumlah_penduduk' => 5670,
                'luas_wilayah' => 18.25,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Bumi Indah',
                'kecamatan' => 'Tajurhalang',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Komplek Bumi Indah, Tajurhalang',
                'kode_pos' => '16320',
                'nama_kepala_desa' => 'Hendra Wijaya',
                'no_telepon' => '021-3456789',
                'jumlah_penduduk' => 3450,
                'luas_wilayah' => 9.60,
                'status' => 'aktif'
            ],
            [
                'nama_desa' => 'Karya Tani',
                'kecamatan' => 'Leuwiliang',
                'kabupaten' => 'Bogor',
                'provinsi' => 'Jawa Barat',
                'alamat_lengkap' => 'Jl. Karya Tani KM 3, Leuwiliang',
                'kode_pos' => '16640',
                'nama_kepala_desa' => 'Made Subrata',
                'no_telepon' => '021-4567890',
                'jumlah_penduduk' => 4120,
                'luas_wilayah' => 13.75,
                'status' => 'aktif'
            ]
        ];

        foreach ($desaData as $desa) {
            Desa::create($desa);
        }
    }
}
