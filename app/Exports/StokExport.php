<?php

namespace App\Exports;

use App\Models\Stok;

class StokExport extends DataExport
{
    public function __construct()
    {
        $data = Stok::with(['pupuk', 'pupuk.supplier', 'pengguna'])->get()->map(function($item) {
            return [
                'ID' => $item->id,
                'Pupuk' => $item->pupuk ? $item->pupuk->nama_pupuk : '-',
                'Jumlah Stok' => $item->jumlah_stok,
                'Status' => ucfirst(str_replace('_', ' ', $item->status_stok)),
                'Lokasi Gudang' => $item->lokasi_gudang ?? '-',
                'Supplier' => $item->pupuk && $item->pupuk->supplier ? $item->pupuk->supplier->nama_supplier : '-',
                'Pengguna' => $item->pengguna ? $item->pengguna->nama : '-',
            ];
        });

        $headings = [
            'ID',
            'Pupuk',
            'Jumlah Stok',
            'Status',
            'Lokasi Gudang',
            'Supplier',
            'Pengguna',
        ];

        parent::__construct($data, $headings);
    }
}