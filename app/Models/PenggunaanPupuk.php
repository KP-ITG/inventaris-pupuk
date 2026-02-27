<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggunaanPupuk extends Model
{
    protected $table = 'penggunaan_pupuk';

    protected $fillable = [
        'desa_id',
        'kepala_desa_id',
        'pupuk_id',
        'jumlah_digunakan',
        'keterangan',
        'tanggal_penggunaan',
    ];

    protected $casts = [
        'tanggal_penggunaan' => 'date',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kepalaDesa(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'kepala_desa_id');
    }

    public function pupuk(): BelongsTo
    {
        return $this->belongsTo(Pupuk::class, 'pupuk_id');
    }
}
