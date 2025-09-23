<?php

namespace App\Exports;

use App\Models\KategoriPupuk;

class KategoriExport extends DataExport
{
    public function __construct()
    {
        $data = KategoriPupuk::all()->map(function($item) {
            return [
                'ID' => $item->id,
                'Nama Kategori' => $item->nama_kategori,
                'Deskripsi' => $item->deskripsi ?: '-',
            ];
        });

        $headings = [
            'ID',
            'Nama Kategori',
            'Deskripsi',
        ];

        parent::__construct($data, $headings);
    }
}