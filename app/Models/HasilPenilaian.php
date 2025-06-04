<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPenilaian extends Model
{
    use HasFactory;

    protected $table = 'hasil_penilaian';

    protected $fillable = [
        'penilaian_id',
        'alternatif_id',
        'decision_matrix',
        'preference_matrix',
        'leaving_flow',
        'entering_flow',
        'net_flow',
        'ranking'
    ];

    protected $casts = [
        'decision_matrix' => 'array',
        'preference_matrix' => 'array',
        'leaving_flow' => 'float',
        'entering_flow' => 'float',
        'net_flow' => 'float',
    ];

    // Relationships
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    // Scopes
    public function scopeByRanking($query)
    {
        return $query->orderBy('ranking');
    }

    public function scopeForPenilaian($query, $penilaianId)
    {
        return $query->where('penilaian_id', $penilaianId);
    }

    // Accessors
    public function getFormattedNetFlowAttribute()
    {
        return number_format($this->net_flow, 6);
    }
}