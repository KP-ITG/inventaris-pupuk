<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiPupuk extends Model
{
    protected $table = 'distribusi_pupuk';

    protected $fillable = [
        'nomor_distribusi',
        'pupuk_id',
        'desa_id',
        'pengguna_id',
        'jumlah_distribusi',
        'tanggal_distribusi',
        'tanggal_realisasi',
        'status_distribusi',
        'catatan',
        'nama_penerima',
        'jabatan_penerima',
        'no_telepon_penerima'
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
        'tanggal_realisasi' => 'date',
        'jumlah_distribusi' => 'integer',
    ];

    // Relasi
    public function pupuk(): BelongsTo
    {
        return $this->belongsTo(Pupuk::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    // Helper method untuk generate nomor distribusi
    public static function generateNomorDistribusi()
    {
        $prefix = 'DIST';
        $date = now()->format('Ymd');
        $lastNumber = self::whereDate('created_at', now()->toDateString())
                         ->count() + 1;

        return $prefix . '/' . $date . '/' . str_pad($lastNumber, 3, '0', STR_PAD_LEFT);
    }
}
