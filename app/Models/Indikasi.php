<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Indikasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'indikasis';

    protected $fillable = [
        'penghasilan',
        'pekerjaan',
        'jumlah_tanggungan',
        'jumlah_anak_sekolah',
        'ibu_hamil',
        'balita',
        'anggota_disabilitas',
        'lansia',
        'luas_lantai',
        'jenis_lantai',
        'jenis_dinding',
        'alternatif_id',
        'kriteria_id'
    ];

    protected $casts = [
        'ibu_hamil' => 'string',
        'balita' => 'string',
        'anggota_disabilitas' => 'string',
        'lansia' => 'string',
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }

    public function konversiKePenilaian()
    {
        if (!$this->alternatif) {
            Log::error('Alternatif tidak ditemukan untuk indikasi', ['indikasi' => $this->toArray()]);
            return;
        }

        DB::beginTransaction();
        try {
            // Mapping field ke kriteria_id
            $kriteriaMapping = [
                'penghasilan' => 1,
                'pekerjaan' => 2,
                'jumlah_tanggungan' => 3,
                'jumlah_anak_sekolah' => 4,
                'ibu_hamil' => 5,
                'balita' => 6,
                'anggota_disabilitas' => 7,
                'lansia' => 8,
                'luas_lantai' => 9,
                'jenis_lantai' => 10,
                'jenis_dinding' => 11
            ];

            // Proses setiap kriteria dalam transaction
            foreach ($kriteriaMapping as $field => $kriteriaId) {
                try {
                    $methodName = 'proses' . str_replace('_', '', ucwords($field, '_'));
                    if (method_exists($this, $methodName)) {
                        $this->{$methodName}($this->alternatif);
                    } else {
                        Log::warning("Method $methodName tidak ditemukan");
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal memproses $field: " . $e->getMessage());
                    throw $e; // Re-throw untuk rollback transaction
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal konversi ke penilaian: ' . $e->getMessage());
        }
    }

    protected function prosesPenghasilan($alternatif)
    {
        $subkriteria = match (true) {
            $this->penghasilan <= 600000 => ['nama' => '≤ Rp 600.000/bulan'],
            $this->penghasilan <= 1000000 => ['nama' => 'Rp 600.000 – Rp 1.000.000/bulan'],
            default => ['nama' => '> Rp 1.000.000/bulan']
        };

        $this->simpanPenilaian($alternatif, 1, $subkriteria['nama']);
    }

    protected function prosesPekerjaan($alternatif)
    {
        $subkriteria = match ($this->pekerjaan) {
            'Tidak bekerja' => ['nama' => 'Tidak bekerja'],
            'Pekerja harian lepas' => ['nama' => 'Pekerja harian lepas'],
            'Pekerja tetap' => ['nama' => 'Pekerja tetap'],
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 2, $subkriteria['nama']);
        } else {
            Log::warning('Pekerjaan tidak valid', ['pekerjaan' => $this->pekerjaan]);
        }
    }

    protected function prosesJumlahTanggungan($alternatif)
    {
        $subkriteria = match (true) {
            $this->jumlah_tanggungan >= 5 => ['nama' => '≥5 orang'],
            $this->jumlah_tanggungan >= 3 => ['nama' => '3-4 orang'],
            default => ['nama' => '≤2 orang']
        };

        $this->simpanPenilaian($alternatif, 3, $subkriteria['nama']);
    }

    protected function prosesJumlahAnakSekolah($alternatif)
    {
        $subkriteria = match (true) {
            $this->jumlah_anak_sekolah >= 3 => ['nama' => '≥3 anak'],
            $this->jumlah_anak_sekolah == 2 => ['nama' => '2 anak'],
            $this->jumlah_anak_sekolah == 1 => ['nama' => '1 anak'],
            default => ['nama' => 'Tidak ada anak sekolah']
        };

        $this->simpanPenilaian($alternatif, 4, $subkriteria['nama']);
    }

    protected function prosesIbuHamil($alternatif)
    {
        $subkriteria = match ($this->ibu_hamil) {
            'Ada' => ['nama' => 'Ada'],
            default => ['nama' => 'Tidak ada']
        };

        $this->simpanPenilaian($alternatif, 5, $subkriteria['nama']);
    }

    protected function prosesBalita($alternatif)
    {
        $subkriteria = match ($this->balita) {
            'Ada' => ['nama' => 'Ada'],
            default => ['nama' => 'Tidak ada']
        };

        $this->simpanPenilaian($alternatif, 6, $subkriteria['nama']);
    }

    protected function prosesAnggotaDisabilitas($alternatif)
    {
        $subkriteria = match ($this->anggota_disabilitas) {
            'Ada' => ['nama' => 'Ada'],
            default => ['nama' => 'Tidak ada']
        };

        $this->simpanPenilaian($alternatif, 7, $subkriteria['nama']);
    }

    protected function prosesLansia($alternatif)
    {
        $subkriteria = match ($this->lansia) {
            'Ada' => ['nama' => 'Ada'],
            default => ['nama' => 'Tidak ada']
        };

        $this->simpanPenilaian($alternatif, 8, $subkriteria['nama']);
    }

    protected function prosesLuasLantai($alternatif)
    {
        $subkriteria = match ($this->luas_lantai) {
            '<8 m² per orang' => ['nama' => '<8 m² per orang'],
            '8-15 m² per orang' => ['nama' => '8-15 m² per orang'],
            '>15 m² per orang' => ['nama' => '>15 m² per orang'],
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 9, $subkriteria['nama']);
        } else {
            Log::warning('Luas Lantai tidak valid', ['luas_lantai' => $this->luas_lantai]);
        }
    }

    protected function prosesJenisLantai($alternatif)
    {
        $jenisLantai = strtolower($this->jenis_lantai);

        $subkriteria = match ($jenisLantai) {
            'tanah' => ['nama' => 'Tanah'],
            'bambu' => ['nama' => 'Bambu'],
            'semen' => ['nama' => 'Semen'],
            'keramik' => ['nama' => 'Keramik'],
            default => null
        };

        if ($subkriteria) {
            $this->simpanPenilaian($alternatif, 10, $subkriteria['nama']);
        } else {
            Log::warning('Jenis lantai tidak valid', ['jenis_lantai' => $this->jenis_lantai]);
        }
    }

    protected function prosesJenisDinding($alternatif)
    {
        $jenis = strtolower($this->jenis_dinding);

        if (str_contains($jenis, 'bambu') || str_contains($jenis, 'rumbia') || str_contains($jenis, 'kayu rendah')) {
            $this->simpanPenilaian($alternatif, 11, 'Bambu/rumbia/kayu rendah');
        } elseif (str_contains($jenis, 'tembok') || str_contains($jenis, 'semen')) {
            $this->simpanPenilaian($alternatif, 11, 'Tembok/Semen');
        } else {
            Log::warning('Jenis dinding tidak valid', ['jenis_dinding' => $this->jenis_dinding]);
        }
    }

    protected function simpanPenilaian($alternatif, $kriteriaId, $namaSubkriteria)
    {
        $subkriteria = SubKriteria::where('kriteria_id', $kriteriaId)
            ->whereRaw('LOWER(nama_sub_kriteria) = ?', [strtolower($namaSubkriteria)])
            ->first();

        if (!$subkriteria) {
            Log::error('Subkriteria tidak ditemukan', [
                'kriteria_id' => $kriteriaId,
                'nama_sub_kriteria' => $namaSubkriteria,
                'alternatif_id' => $alternatif->id
            ]);
            return;
        }

        try {
            // Update atau create dengan kondisi yang lebih spesifik
            $penilaian = Penilaian::updateOrCreate(
                [
                    'alternatif_id' => $alternatif->id,
                    'kriteria_id' => $kriteriaId
                ],
                [
                    'subkriteria_id' => $subkriteria->id,
                ]
            );

            Log::info('Penilaian berhasil diproses', $penilaian->toArray());
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan penilaian', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}