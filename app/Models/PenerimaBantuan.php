<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PenerimaBantuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'nik',
        'nama',
        'penghasilan',
        'jml_tanggungan',
        'pekerjaan'
    ];
}
