<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $table = 'desa';

    protected $fillable = [
        'nama_desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'alamat_lengkap',
        'kode_pos',
        'nama_kepala_desa',
        'no_telepon',
        'jumlah_penduduk',
        'luas_wilayah',
        'status'
    ];

    protected $casts = [
        'jumlah_penduduk' => 'integer',
        'luas_wilayah' => 'decimal:2',
    ];

    // Relasi dengan distribusi pupuk
    public function distribusiPupuk(): HasMany
    {
        return $this->hasMany(DistribusiPupuk::class);
    }

    // Helper method untuk format alamat lengkap
    public function getAlamatLengkapAttribute($value)
    {
        return $value ?: "{$this->nama_desa}, Kec. {$this->kecamatan}, {$this->kabupaten}, {$this->provinsi}";
    }
}
