<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penerima extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'nama',
        'nik',
        'tmpt_tgl_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'desa_id',
    ];
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}