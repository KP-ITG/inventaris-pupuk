<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama',
        'email',
        'password_hash',
        'role',
        'alamat',
        'kontak',
        'profile_picture'
    ];

    protected $hidden = [
        'password_hash'
    ];

    /**
     * Relasi ke tabel stok
     */
    public function stok(): HasMany
    {
        return $this->hasMany(Stok::class, 'pengguna_id');
    }

    /**
     * Relasi ke tabel transaksi distribusi
     */
    public function transaksiDistribusi(): HasMany
    {
        return $this->hasMany(TransaksiDistribusi::class, 'pengguna_id');
    }
}