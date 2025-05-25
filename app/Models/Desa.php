<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Desa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'id',
        'nama_desa',
    ];

    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
    public function penerima()
    {
    	return $this->hasOne(Penerima::class);
    }

    public function bioDatas()
    {
        return $this->hasManyThrough(BioData::class, Alternatif::class);
    }
}
