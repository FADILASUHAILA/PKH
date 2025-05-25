<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alternatif extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'kode',
        'nama',
        'desa_id',
    ];

    public function kriterias(): HasMany
    {
        return $this->hasMany(Kriteria::class);
    }
    
    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }
    
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
    
    public function biodata(): HasOne
    {
        return $this->hasOne(BioData::class, 'alternatif_id');
    }
}