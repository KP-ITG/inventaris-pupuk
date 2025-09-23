<?php

namespace App\Exports;

use App\Models\TransaksiDistribusi;

class DistribusiExport extends DataExport
{
    public function __construct()
    {
        $data = TransaksiDistribusi::with(['desa', 'items.pupuk'])->get()->map(function($item) {
            $pupukList = $item->items->map(function($distribusi) {
                return $distribusi->pupuk->nama_pupuk . ' (' . $distribusi->jumlah_kg . 'kg)';
            })->implode(', ');

            return [
                'ID' => $item->id,
                'Nomor Transaksi' => $item->nomor_transaksi,
                'Desa' => $item->desa ? $item->desa->nama_desa : '-',
                'Pupuk' => $pupukList,
                'Total Distribusi (kg)' => $item->items->sum('jumlah_kg') . ' kg',
                'Tanggal Distribusi' => $item->tanggal_distribusi->format('d/m/Y'),
                'Keterangan' => $item->keterangan ?: '-',
            ];
        });

        $headings = [
            'ID',
            'Nomor Transaksi',
            'Desa',
            'Pupuk',
            'Total Distribusi (kg)',
            'Tanggal Distribusi',
            'Keterangan',
        ];

        parent::__construct($data, $headings);
    }
}