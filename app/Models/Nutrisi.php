<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Nutrisi extends Model
{
    protected $table = 'nutrisi';

    protected $fillable = [
        'nama_nutrisi',
        'formula_kimia',
        'deskripsi_nutrisi'
    ];

    /**
     * Relasi many-to-many dengan pupuk melalui tabel pupuk_nutrisi
     */
    public function pupuk(): BelongsToMany
    {
        return $this->belongsToMany(Pupuk::class, 'pupuk_nutrisi', 'nutrisi_id', 'pupuk_id')
                    ->withPivot('kandungan_persen')
                    ->withTimestamps();
    }
}