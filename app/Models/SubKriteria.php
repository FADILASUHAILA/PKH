<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SubKriteria extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'kriteria_id',
        'nama_sub_kriteria',
        'nilai',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id'); // Sesuaikan dengan nama kolom foreign key jika berbeda
    }
}


