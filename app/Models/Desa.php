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
        'nama',
    ];

    public function desas()
    {
        return $this->hasMany(Desa::class);
    }
    public function penerima()
    {
    	return $this->hasOne(Penerima::class);
    }
}
