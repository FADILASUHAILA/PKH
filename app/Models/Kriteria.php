<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'kode',
        'nama_kriteria',
        'bobot',
    ];

    public function subkriterias()
    {
        return $this->hasMany(SubKriteria::class);
    }
    public function hasilPenilaian()
    {
        return $this->hasMany(HasilPenilaian::class);
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function indikasi(): HasMany
    {
        return $this->hasMany(Indikasi::class, 'kriteria_id');
    }


    

    
}