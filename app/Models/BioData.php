<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BioData extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nik',
        'alamat',
        'no_hp',
        'alternatif_id'
    ];

    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class);
    }
}