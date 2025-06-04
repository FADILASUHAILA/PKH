<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penilaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'id',
        'alternatif_id',
        'kriteria_id',
        'subkriteria_id',
        'nilai',
        'penilaian_id'
    ];

    public function hasilPenilaian()
    {
        return $this->hasMany(HasilPenilaian::class);
    }
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    // Relasi ke Alternatif
    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }
    public function subkriteria()
    {
        return $this->belongsTo(SubKriteria::class);
    }
}
