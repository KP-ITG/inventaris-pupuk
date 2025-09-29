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
                'Nama Nutrisi' => $item->nama_nutrisi,
                'Satuan' => $item->satuan ?: '-',
                'Formula Kimia' => $item->formula_kimia ?: '-',
                'Deskripsi' => $item->deskripsi_nutrisi ?: '-',
            ];
        });

        $headings = [
            'ID',
            'Nama Nutrisi',
            'Satuan',
            'Formula Kimia',
            'Deskripsi',
        ];

        parent::__construct($data, $headings);
    }
}