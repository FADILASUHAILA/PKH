<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alternatif extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = 
    [
        'kode',
        'nama',
        'alamat',
        'no_hp'
    ];
}
