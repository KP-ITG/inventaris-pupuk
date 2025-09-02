<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

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
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_hash' => 'hashed',
    ];

    // Override password field untuk Laravel Auth
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

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