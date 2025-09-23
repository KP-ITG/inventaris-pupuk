<?php

namespace App\Exports;

use App\Models\Desa;

class DesaExport extends DataExport
{
    public function __construct()
    {
        $data = Desa::all()->map(function($item) {
            return [
                'ID' => $item->id,
                'Nama Desa' => $item->nama_desa,
                'Kecamatan' => $item->kecamatan,
                'Kabupaten' => $item->kabupaten,
                'Provinsi' => $item->provinsi,
                'Kode Pos' => $item->kode_pos ?: '-',
                'Nama Kepala Desa' => $item->nama_kepala_desa ?: '-',
                'No. Telepon' => $item->no_telepon ?: '-',
                'Jumlah Penduduk' => $item->jumlah_penduduk ? number_format($item->jumlah_penduduk, 0, ',', '.') . ' jiwa' : '-',
                'Luas Wilayah' => $item->luas_wilayah ? number_format($item->luas_wilayah, 2, ',', '.') . ' km²' : '-',
                'Status' => $item->status ?: 'Aktif',
            ];
        });

        $headings = [
            'ID',
            'Nama Desa',
            'Kecamatan',
            'Kabupaten',
            'Provinsi',
            'Kode Pos',
            'Nama Kepala Desa',
            'No. Telepon',
            'Jumlah Penduduk',
            'Luas Wilayah',
            'Status',
        ];

        parent::__construct($data, $headings);
    }
}