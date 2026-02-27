<?php

namespace Database\Seeders;

use App\Models\KategoriPupuk;
use App\Models\Supplier;
use App\Models\Nutrisi;
use App\Models\Pupuk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterDataSeeder extends Seeder
{
    /**
     * Seed master data: KategoriPupuk, Supplier, Nutrisi, Pupuk, PupukNutrisi.
     * Tidak termasuk data Pengguna dan Desa.
     */
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1. Kategori Pupuk
        // ---------------------------------------------------------------
        $kategoriData = [
            ['nama_kategori' => 'Pupuk Organik',   'deskripsi' => 'Pupuk yang berasal dari bahan-bahan organik alami seperti kompos, pupuk kandang, dan humus'],
            ['nama_kategori' => 'Pupuk Anorganik',  'deskripsi' => 'Pupuk buatan atau sintetis yang mengandung unsur hara dalam bentuk senyawa kimia'],
            ['nama_kategori' => 'Pupuk NPK',        'deskripsi' => 'Pupuk majemuk yang mengandung Nitrogen (N), Fosfor (P), dan Kalium (K) sekaligus'],
            ['nama_kategori' => 'Pupuk Urea',       'deskripsi' => 'Pupuk nitrogen tunggal dengan kadar N tinggi untuk memacu pertumbuhan vegetatif'],
            ['nama_kategori' => 'Pupuk SP-36',      'deskripsi' => 'Pupuk tunggal sumber Fosfor (P2O5) untuk merangsang pembentukan akar dan bunga'],
            ['nama_kategori' => 'Pupuk KCl',        'deskripsi' => 'Pupuk tunggal sumber Kalium (K2O) untuk meningkatkan ketahanan dan kualitas hasil panen'],
        ];

        $kategoris = [];
        foreach ($kategoriData as $data) {
            $kategoris[$data['nama_kategori']] = KategoriPupuk::firstOrCreate(
                ['nama_kategori' => $data['nama_kategori']],
                ['deskripsi'     => $data['deskripsi']]
            );
        }

        // ---------------------------------------------------------------
        // 2. Supplier
        // ---------------------------------------------------------------
        $supplierData = [
            [
                'nama_supplier' => 'PT Petrokimia Gresik',
                'alamat'        => 'Jl. Ahmad Yani, Gresik, Jawa Timur 61119',
                'kontak'        => '031-3982100',
            ],
            [
                'nama_supplier' => 'PT Pupuk Kaltim',
                'alamat'        => 'Jl. James Simandjuntak No. 1, Bontang, Kalimantan Timur 75313',
                'kontak'        => '0548-41202',
            ],
            [
                'nama_supplier' => 'PT Pupuk Iskandar Muda',
                'alamat'        => 'Jl. Banda Aceh – Medan Km. 05, Krueng Geukueh, Aceh Utara 24354',
                'kontak'        => '0645-61100',
            ],
            [
                'nama_supplier' => 'PT Pupuk Sriwidjaja Palembang',
                'alamat'        => 'Jl. Mayor Zen, Palembang, Sumatera Selatan 30118',
                'kontak'        => '0711-712111',
            ],
        ];

        $suppliers = [];
        foreach ($supplierData as $data) {
            $suppliers[$data['nama_supplier']] = Supplier::firstOrCreate(
                ['nama_supplier' => $data['nama_supplier']],
                ['alamat' => $data['alamat'], 'kontak' => $data['kontak']]
            );
        }

        // ---------------------------------------------------------------
        // 3. Nutrisi
        // ---------------------------------------------------------------
        $nutrisiData = [
            ['nama_nutrisi' => 'Nitrogen',    'satuan' => '%',   'formula_kimia' => 'N',    'deskripsi_nutrisi' => 'Unsur hara makro primer untuk pertumbuhan vegetatif, pembentukan klorofil dan protein'],
            ['nama_nutrisi' => 'Fosfor',      'satuan' => '%',   'formula_kimia' => 'P2O5', 'deskripsi_nutrisi' => 'Unsur hara makro primer untuk pembentukan akar, bunga, biji, dan transfer energi'],
            ['nama_nutrisi' => 'Kalium',      'satuan' => '%',   'formula_kimia' => 'K2O',  'deskripsi_nutrisi' => 'Unsur hara makro primer untuk ketahanan tanaman, regulasi air, dan kualitas buah'],
            ['nama_nutrisi' => 'Magnesium',   'satuan' => '%',   'formula_kimia' => 'MgO',  'deskripsi_nutrisi' => 'Unsur hara sekunder untuk pembentukan klorofil dan aktivasi enzim'],
            ['nama_nutrisi' => 'Sulfur',      'satuan' => '%',   'formula_kimia' => 'S',    'deskripsi_nutrisi' => 'Unsur hara sekunder untuk sintesis protein dan pembentukan vitamin'],
            ['nama_nutrisi' => 'Kalsium',     'satuan' => '%',   'formula_kimia' => 'CaO',  'deskripsi_nutrisi' => 'Unsur hara sekunder untuk struktur dinding sel dan aktivitas meristematik'],
            ['nama_nutrisi' => 'Besi',        'satuan' => 'ppm', 'formula_kimia' => 'Fe',   'deskripsi_nutrisi' => 'Unsur hara mikro untuk sintesis klorofil dan transfer elektron fotosintesis'],
            ['nama_nutrisi' => 'Zinc',        'satuan' => 'ppm', 'formula_kimia' => 'Zn',   'deskripsi_nutrisi' => 'Unsur hara mikro untuk aktivasi enzim, sintesis auksin, dan pembentukan protein'],
            ['nama_nutrisi' => 'Mangan',      'satuan' => 'ppm', 'formula_kimia' => 'Mn',   'deskripsi_nutrisi' => 'Unsur hara mikro untuk aktivasi enzim dan proses fotosintesis'],
            ['nama_nutrisi' => 'Boron',       'satuan' => 'ppm', 'formula_kimia' => 'B',    'deskripsi_nutrisi' => 'Unsur hara mikro untuk pembentukan dinding sel, penyerbukan, dan transportasi gula'],
        ];

        $nutrisMap = [];
        foreach ($nutrisiData as $data) {
            $nutrisMap[$data['nama_nutrisi']] = Nutrisi::firstOrCreate(
                ['nama_nutrisi' => $data['nama_nutrisi']],
                [
                    'satuan'          => $data['satuan'],
                    'formula_kimia'   => $data['formula_kimia'],
                    'deskripsi_nutrisi' => $data['deskripsi_nutrisi'],
                ]
            );
        }

        // ---------------------------------------------------------------
        // 4. Pupuk  +  5. Pupuk Nutrisi (pivot)
        // ---------------------------------------------------------------
        $pupukData = [
            // --- Urea ---
            [
                'nama_pupuk'       => 'Urea 46%',
                'kode_produk'      => 'URE-46',
                'kategori'         => 'Pupuk Urea',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk Urea butiran putih dengan kandungan Nitrogen 46%, cocok untuk semua jenis tanaman',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 2100,
                'harga_jual'       => 2500,
                'stok_gudang_pusat'=> 5000,
                'tanggal_produksi' => '2025-01-10',
                'tanggal_kadaluarsa'=> '2027-01-10',
                'nutrisi' => [
                    ['Nitrogen', 46.00],
                ],
            ],
            // --- NPK ---
            [
                'nama_pupuk'       => 'NPK Phonska 15-15-15',
                'kode_produk'      => 'NPK-151515',
                'kategori'         => 'Pupuk NPK',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk NPK majemuk dengan komposisi seimbang 15% N, 15% P2O5, 15% K2O, dilengkapi sulfur dan magnesium',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 2900,
                'harga_jual'       => 3200,
                'stok_gudang_pusat'=> 4000,
                'tanggal_produksi' => '2025-02-01',
                'tanggal_kadaluarsa'=> '2027-02-01',
                'nutrisi' => [
                    ['Nitrogen', 15.00],
                    ['Fosfor',   15.00],
                    ['Kalium',   15.00],
                    ['Sulfur',   10.00],
                    ['Magnesium', 3.00],
                ],
            ],
            [
                'nama_pupuk'       => 'NPK Mutiara 16-16-16',
                'kode_produk'      => 'NPK-161616',
                'kategori'         => 'Pupuk NPK',
                'supplier'         => 'PT Pupuk Kaltim',
                'deskripsi'        => 'Pupuk NPK granul biru dengan kandungan N-P-K masing-masing 16%, formulasi untuk tanaman pangan dan hortikultura',
                'berat_kemasan_kg' => 25.00,
                'harga_beli'       => 3100,
                'harga_jual'       => 3500,
                'stok_gudang_pusat'=> 3500,
                'tanggal_produksi' => '2025-03-01',
                'tanggal_kadaluarsa'=> '2027-03-01',
                'nutrisi' => [
                    ['Nitrogen', 16.00],
                    ['Fosfor',   16.00],
                    ['Kalium',   16.00],
                ],
            ],
            [
                'nama_pupuk'       => 'NPK Kujang 30-6-8',
                'kode_produk'      => 'NPK-3068',
                'kategori'         => 'Pupuk NPK',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk NPK dengan N tinggi 30%, cocok untuk fase vegetatif tanaman padi dan jagung',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 3400,
                'harga_jual'       => 3800,
                'stok_gudang_pusat'=> 2000,
                'tanggal_produksi' => '2025-04-15',
                'tanggal_kadaluarsa'=> '2027-04-15',
                'nutrisi' => [
                    ['Nitrogen', 30.00],
                    ['Fosfor',    6.00],
                    ['Kalium',    8.00],
                ],
            ],
            // --- SP-36 ---
            [
                'nama_pupuk'       => 'SP-36',
                'kode_produk'      => 'SP36-001',
                'kategori'         => 'Pupuk SP-36',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk tunggal sumber Fosfor dengan kandungan P2O5 36%, untuk memperkuat sistem perakaran dan merangsang pembungaan',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 2500,
                'harga_jual'       => 2800,
                'stok_gudang_pusat'=> 3000,
                'tanggal_produksi' => '2025-01-20',
                'tanggal_kadaluarsa'=> '2027-01-20',
                'nutrisi' => [
                    ['Fosfor', 36.00],
                    ['Sulfur',  5.00],
                    ['Kalsium', 8.00],
                ],
            ],
            // --- KCl ---
            [
                'nama_pupuk'       => 'KCl 60%',
                'kode_produk'      => 'KCL-60',
                'kategori'         => 'Pupuk KCl',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk tunggal sumber Kalium dengan kandungan K2O 60%, untuk meningkatkan kualitas dan ketahanan tanaman',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 3800,
                'harga_jual'       => 4200,
                'stok_gudang_pusat'=> 2500,
                'tanggal_produksi' => '2025-02-10',
                'tanggal_kadaluarsa'=> '2027-02-10',
                'nutrisi' => [
                    ['Kalium', 60.00],
                ],
            ],
            // --- Organik ---
            [
                'nama_pupuk'       => 'Pupuk Kompos Petroganik',
                'kode_produk'      => 'ORG-PET-001',
                'kategori'         => 'Pupuk Organik',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk organik granul berbahan baku kompos dengan kandungan C-organik minimal 15%, memperbaiki struktur tanah',
                'berat_kemasan_kg' => 40.00,
                'harga_beli'       => 900,
                'harga_jual'       => 1200,
                'stok_gudang_pusat'=> 6000,
                'tanggal_produksi' => '2025-01-05',
                'tanggal_kadaluarsa'=> '2026-12-31',
                'nutrisi' => [
                    ['Nitrogen',  2.50],
                    ['Fosfor',    1.50],
                    ['Kalium',    1.50],
                    ['Magnesium', 0.30],
                ],
            ],
            [
                'nama_pupuk'       => 'Pupuk Kandang Ayam Granul',
                'kode_produk'      => 'ORG-KAY-001',
                'kategori'         => 'Pupuk Organik',
                'supplier'         => 'PT Pupuk Sriwidjaja Palembang',
                'deskripsi'        => 'Pupuk organik granul berbahan kotoran ayam yang telah difermentasi, kaya unsur hara makro dan mikro',
                'berat_kemasan_kg' => 25.00,
                'harga_beli'       => 1100,
                'harga_jual'       => 1500,
                'stok_gudang_pusat'=> 4500,
                'tanggal_produksi' => '2025-03-10',
                'tanggal_kadaluarsa'=> '2026-09-10',
                'nutrisi' => [
                    ['Nitrogen',  3.00],
                    ['Fosfor',    2.20],
                    ['Kalium',    2.50],
                    ['Kalsium',   1.80],
                    ['Magnesium', 0.60],
                ],
            ],
            // --- Anorganik ---
            [
                'nama_pupuk'       => 'ZA (Zwavelzure Ammoniak)',
                'kode_produk'      => 'ZA-001',
                'kategori'         => 'Pupuk Anorganik',
                'supplier'         => 'PT Petrokimia Gresik',
                'deskripsi'        => 'Pupuk Amonium Sulfat dengan kandungan N 21% dan S 24%, cocok untuk tanah alkalin dan tanaman yang membutuhkan sulfur',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 1700,
                'harga_jual'       => 2000,
                'stok_gudang_pusat'=> 3200,
                'tanggal_produksi' => '2025-05-01',
                'tanggal_kadaluarsa'=> '2027-05-01',
                'nutrisi' => [
                    ['Nitrogen', 21.00],
                    ['Sulfur',   24.00],
                ],
            ],
            [
                'nama_pupuk'       => 'Dolomit (Kapur Pertanian)',
                'kode_produk'      => 'DOL-001',
                'kategori'         => 'Pupuk Anorganik',
                'supplier'         => 'PT Pupuk Kaltim',
                'deskripsi'        => 'Kapur pertanian berbahan dolomit mengandung CaO dan MgO, digunakan untuk menetralkan pH tanah masam',
                'berat_kemasan_kg' => 50.00,
                'harga_beli'       => 700,
                'harga_jual'       => 900,
                'stok_gudang_pusat'=> 5500,
                'tanggal_produksi' => '2025-06-01',
                'tanggal_kadaluarsa'=> '2027-06-01',
                'nutrisi' => [
                    ['Kalsium',   30.00],
                    ['Magnesium', 18.00],
                ],
            ],
            [
                'nama_pupuk'       => 'Pupuk Mikro Gandasil B',
                'kode_produk'      => 'MIK-GAN-B',
                'kategori'         => 'Pupuk Anorganik',
                'supplier'         => 'PT Pupuk Iskandar Muda',
                'deskripsi'        => 'Pupuk daun majemuk lengkap mengandung unsur hara mikro (Fe, Zn, Mn, B) dan makro untuk fase pembungaan dan pembuahan',
                'berat_kemasan_kg' => 0.25,
                'harga_beli'       => 18000,
                'harga_jual'       => 24000,
                'stok_gudang_pusat'=> 800,
                'tanggal_produksi' => '2025-07-01',
                'tanggal_kadaluarsa'=> '2027-07-01',
                'nutrisi' => [
                    ['Nitrogen',  6.00],
                    ['Fosfor',   20.00],
                    ['Kalium',   30.00],
                    ['Besi',    500.00],
                    ['Zinc',    300.00],
                    ['Mangan',  200.00],
                    ['Boron',   150.00],
                ],
            ],
        ];

        foreach ($pupukData as $data) {
            $nutrisiRelasi  = $data['nutrisi'];
            $namaKategori   = $data['kategori'];
            $namaSupplier   = $data['supplier'];

            unset($data['nutrisi'], $data['kategori'], $data['supplier']);

            $data['kategori_id'] = $kategoris[$namaKategori]->id ?? null;
            $data['supplier_id'] = $suppliers[$namaSupplier]->id ?? null;

            /** @var \App\Models\Pupuk $pupuk */
            $pupuk = Pupuk::firstOrCreate(
                ['kode_produk' => $data['kode_produk']],
                $data
            );

            // Pupuk Nutrisi pivot
            foreach ($nutrisiRelasi as [$namaNutrisi, $kandungan]) {
                if (isset($nutrisMap[$namaNutrisi])) {
                    DB::table('pupuk_nutrisi')->updateOrInsert(
                        [
                            'pupuk_id'   => $pupuk->id,
                            'nutrisi_id' => $nutrisMap[$namaNutrisi]->id,
                        ],
                        ['kandungan_persen' => $kandungan, 'updated_at' => now(), 'created_at' => now()]
                    );
                }
            }
        }

        $this->command->info('MasterDataSeeder selesai: KategoriPupuk, Supplier, Nutrisi, Pupuk, dan PupukNutrisi berhasil di-seed.');
    }
}
