<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pupuk extends Model
{
    protected $table = 'pupuk';

    protected $fillable = [
        'nama_pupuk',
        'kode_produk',
        'deskripsi',
        'berat_kemasan_kg',
        'tanggal_produksi',
        'tanggal_kadaluarsa',
        'harga_beli',
        'harga_jual',
        'stok_gudang_pusat',
        'kategori_id',
        'supplier_id'
    ];

    protected $casts = [
        'tanggal_produksi' => 'date',
        'tanggal_kadaluarsa' => 'date',
        'berat_kemasan_kg' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2'
    ];

    /**
     * Relasi ke kategori pupuk
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPupuk::class, 'kategori_id');
    }

    /**
     * Relasi ke supplier
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /**
     * Relasi many-to-many dengan nutrisi melalui tabel pupuk_nutrisi
     */
    public function nutrisi(): BelongsToMany
    {
        return $this->belongsToMany(Nutrisi::class, 'pupuk_nutrisi', 'pupuk_id', 'nutrisi_id')
                    ->withPivot('kandungan_persen')
                    ->withTimestamps();
    }

    /**
     * Relasi ke tabel stok
     */
    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'pupuk_id');
    }

    /**
     * Relasi ke tabel transaksi distribusi
     */
    public function transaksiDistribusi(): HasMany
    {
        return $this->hasMany(TransaksiDistribusi::class, 'pupuk_id');
    }
}