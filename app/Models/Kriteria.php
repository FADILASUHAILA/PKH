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
    ];

    public function subkriterias()
    {
        return $this->hasMany(SubKriteria::class);
    }
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }


    

    
}