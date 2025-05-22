<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPenilaian extends Model
{
      protected $fillable =
    [
        'kriteria_id',
        'alternatif_id',
    ];


public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id'); // Sesuaikan dengan nama kolom foreign key jika berbeda
    }
  
    public function kriteria()
    {
        return $this->belongsTo(Alternatif::class, 'kriteria_id'); // Sesuaikan dengan nama kolom foreign key jika berbeda
    }



}
