<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianHeader extends Model
{
    protected $fillable =
    [
        'id',
        'alternatif_id',
        'tanggal_penilaian',
        'catatan',
    ];

    public function details()
{
    return $this->hasMany(Penilaian::class, 'header_id');
}
}
