<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penilaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =
    [
        'id',
        'alternatif_id',
        'kode',
        'desa_id',
        'nilai_kriteria1',
        'nilai_kriteria2',

    ];
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    // Relasi ke Alternatif
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }
}
