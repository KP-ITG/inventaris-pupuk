<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistribusiStatusLog extends Model
{
    protected $table = 'distribusi_status_log';

    protected $fillable = [
        'distribusi_pupuk_id',
        'status_lama',
        'status_baru',
        'user_id',
        'keterangan'
    ];

    public function distribusiPupuk()
    {
        return $this->belongsTo(DistribusiPupuk::class, 'distribusi_pupuk_id');
    }

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }
}
