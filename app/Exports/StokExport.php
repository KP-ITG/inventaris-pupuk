<?php

namespace App\Exports;

use App\Models\Stok;

class StokExport extends DataExport
{
    public function __construct()
    {
        $data = Stok::with(['pupuk', 'supplier'])->get()->map(function($item) {
            return [
                'ID' => $item->id,
                'Pupuk' => $item->pupuk ? $item->pupuk->nama_pupuk : '-',
                'Jumlah (kg)' => $item->jumlah_kg,
                'Harga Beli/kg' => 'Rp ' . number_format($item->harga_beli_per_kg, 0, ',', '.'),
                'Total Nilai' => 'Rp ' . number_format($item->jumlah_kg * $item->harga_beli_per_kg, 0, ',', '.'),
                'Supplier' => $item->supplier ? $item->supplier->nama_supplier : '-',
                'Tanggal Masuk' => $item->tanggal_masuk->format('d/m/Y'),
            ];
        });

        $headings = [
            'ID',
            'Pupuk',
            'Jumlah (kg)',
            'Harga Beli/kg',
            'Total Nilai',
            'Supplier',
            'Tanggal Masuk',
        ];

        parent::__construct($data, $headings);
    }
}