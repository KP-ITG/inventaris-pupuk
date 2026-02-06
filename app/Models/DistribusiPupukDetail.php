<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiPupukDetail extends Model
{
    protected $table = 'distribusi_pupuk_detail';

    protected $fillable = [
        'distribusi_pupuk_id',
        'pupuk_id',
        'jumlah_distribusi',
        'catatan_item'
    ];

    protected $casts = [
        'jumlah_distribusi' => 'integer',
    ];

    // Relasi
    public function distribusiPupuk(): BelongsTo
    {
        return $this->belongsTo(DistribusiPupuk::class);
    }

    public function pupuk(): BelongsTo
    {
        return $this->belongsTo(Pupuk::class);
    }
}
