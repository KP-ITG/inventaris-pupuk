<?php

namespace App\Exports;

use App\Models\Nutrisi;

class NutrisiExport extends DataExport
{
    public function __construct()
    {
        $data = Nutrisi::all()->map(function($item) {
            return [
                'ID' => $item->id,
                'Kode Nutrisi' => $item->kode_nutrisi,
                'Nama Nutrisi' => $item->nama_nutrisi,
                'Satuan' => $item->satuan ?: '-',
                'Deskripsi' => $item->deskripsi ?: '-',
            ];
        });

        $headings = [
            'ID',
            'Kode Nutrisi',
            'Nama Nutrisi',
            'Satuan',
            'Deskripsi',
        ];

        parent::__construct($data, $headings);
    }
}