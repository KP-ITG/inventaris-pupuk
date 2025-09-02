<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'supplier';

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'kontak'
    ];

    /**
     * Relasi ke tabel pupuk
     */
    public function pupuk(): HasMany
    {
        return $this->hasMany(Pupuk::class, 'supplier_id');
    }
}