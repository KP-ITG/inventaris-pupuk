<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransaksiDistribusi extends Model
{
    protected $table = 'transaksi_distribusi';

    protected $fillable = [
        'kode_transaksi',
        'pengguna_id',
        'pupuk_id',
        'jumlah',
        'tanggal_transaksi',
        'harga_saat_transaksi',
        'status'
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'harga_saat_transaksi' => 'decimal:2'
    ];

    /**
     * Relasi ke pengguna
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * Relasi ke pupuk
     */
    public function pupuk(): BelongsTo
    {
        return $this->belongsTo(Pupuk::class, 'pupuk_id');
    }
}