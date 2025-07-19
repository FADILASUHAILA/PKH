<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianHeader extends Model
{
    use HasFactory;

    protected $table = 'penilaian_headers';

    protected $fillable = [
        'alternatif_id',
        'tanggal_penilaian',
        'catatan'
    ];

    protected $casts = [
        'tanggal_penilaian' => 'datetime',
    ];

    // Relationships
    public function details()
    {
        return $this->hasMany(Penilaian::class, 'header_id');
    }

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function hasilPenilaian()
    {
        return $this->hasMany(HasilPenilaian::class, 'header_id');
    }

    // Scopes
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_penilaian', 'desc');
    }
}
