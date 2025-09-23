<?php

namespace App\Exports;

use App\Models\Pupuk;
use Illuminate\Support\Collection;

class PupukExport extends DataExport
{
    public function __construct()
    {
        $data = Pupuk::with(['kategori', 'nutrisi'])->get()->map(function($item) {
            $nutrisiStr = $item->nutrisi->map(function($n) {
                return $n->nama_nutrisi . ' (' . $n->pivot->kandungan_persen . '%)';
            })->implode(', ');

            return [
                'ID' => $item->id,
                'Nama Pupuk' => $item->nama_pupuk,
                'Kategori' => $item->kategori ? $item->kategori->nama_kategori : '-',
                'Harga Jual' => 'Rp ' . number_format($item->harga_jual, 0, ',', '.'),
                'Nutrisi' => $nutrisiStr ?: '-',
                'Deskripsi' => $item->deskripsi ?: '-',
            ];
        });

        $headings = [
            'ID',
            'Nama Pupuk',
            'Kategori',
            'Harga Jual',
            'Nutrisi',
            'Deskripsi',
        ];

        parent::__construct($data, $headings);
    }
}