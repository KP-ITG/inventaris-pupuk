<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    protected $table = 'stok';

    protected $fillable = [
        'pengguna_id',
        'pupuk_id',
        'jumlah_stok'
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