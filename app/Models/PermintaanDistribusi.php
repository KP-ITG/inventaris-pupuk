<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDistribusi extends Model
{
    protected $table = 'permintaan_distribusi';

    protected $fillable = [
        'kepala_desa_id',
        'desa_id',
        'items',
        'keterangan',
        'status',
        'alasan_penolakan',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'items' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relasi ke pengguna yang mengajukan (kepala desa)
    public function kepalaDesa()
    {
        return $this->belongsTo(Pengguna::class, 'kepala_desa_id');
    }

    // Relasi ke desa
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    // Relasi ke pengguna yang approve/reject
    public function approver()
    {
        return $this->belongsTo(Pengguna::class, 'approved_by');
    }

    // Relasi ke distribusi pupuk yang terbuat dari permintaan ini
    public function distribusiPupuk()
    {
        return $this->hasMany(DistribusiPupuk::class);
    }
}
