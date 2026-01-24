<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'pupuk_id',
        'pengguna_id',
        'jumlah_stok',
        'stok_minimum',
        'stok_maksimum',
        'lokasi_gudang',
        'status_stok'
    ];

    protected $casts = [
        'jumlah_stok' => 'integer',
        'stok_minimum' => 'integer',
        'stok_maksimum' => 'integer',
    ];

    /**
     * Relasi ke pupuk
     */
    public function pupuk(): BelongsTo
    {
        return $this->belongsTo(Pupuk::class, 'pupuk_id');
    }

    /**
     * Update status stok berdasarkan jumlah
     */
    public function updateStatusStok()
    {
        if ($this->jumlah_stok <= 0) {
            $this->status_stok = 'habis';
        } elseif ($this->jumlah_stok <= $this->stok_minimum) {
            $this->status_stok = 'hampir_habis';
        } else {
            $this->status_stok = 'aman';
        }

        $this->save();
    }

    /**
     * Kurangi stok untuk distribusi
     */
    public function kurangiStok($jumlah)
    {
        $this->jumlah_stok -= $jumlah;
        $this->updateStatusStok();
        return $this;
    }

    /**
     * Tambah stok (pembelian dari pusat)
     */
    public function tambahStok($jumlah)
    {
        $this->jumlah_stok += $jumlah;
        $this->updateStatusStok();
        return $this;
    }
}