<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'kode',
        'nama_kriteria',
        'bobot',
        'alternatif_id'
    ];

    public function subkriterias()
    {
        return $this->hasMany(SubKriteria::class);
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id'); // Sesuaikan dengan nama kolom foreign key jika berbeda
    }

    
}