<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPenilaian extends Model
{
    use HasFactory;

    protected $table = 'hasil_penilaian';

    protected $fillable = [
        'alternatif_id',
        'leaving_flow',
        'entering_flow',
        'net_flow',
        'ranking'
    ];

    protected $casts = [
        'leaving_flow' => 'decimal:6',
        'entering_flow' => 'decimal:6',
        'net_flow' => 'decimal:6',
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    // Scope untuk query yang sering digunakan
    public function scopeOrderByRanking($query)
    {
        return $query->orderBy('ranking');
    }

    public function scopeWithAlternatif($query)
    {
        return $query->with('alternatif');
    }
}
