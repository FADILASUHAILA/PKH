<?php

namespace App\Services;

use App\Models\Penilaian;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenilaianService
{
    /**
     * Update nilai penilaian berdasarkan subkriteria
     * 
     * @param int|null $alternatifId - ID alternatif spesifik (opsional)
     * @param bool $force - Force update meskipun nilai sudah ada
     * @return array
     */
    public function updateNilaiPenilaian($alternatifId = null, $force = false)
    {
        try {
            $query = Penilaian::with(['subkriteria', 'alternatif']);

            // Filter berdasarkan alternatif jika ditentukan
            if ($alternatifId) {
                $query->where('alternatif_id', $alternatifId);
            }

            // Filter hanya yang belum memiliki nilai, kecuali jika force
            if (!$force) {
                $query->whereNull('nilai');
            }

            $penilaians = $query->get();

            if ($penilaians->isEmpty()) {
                return [
                    'success' => true,
                    'message' => 'Tidak ada data penilaian yang perlu diupdate.',
                    'updated' => 0,
                    'errors' => 0
                ];
            }

            $updated = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($penilaians as $penilaian) {
                try {
                    if ($penilaian->subkriteria) {
                        $oldNilai = $penilaian->nilai;
                        $newNilai = $penilaian->subkriteria->nilai;

                        $penilaian->update([
                            'nilai' => $newNilai
                        ]);

                        $updated++;

                        Log::info('Nilai penilaian berhasil diupdate via service', [
                            'penilaian_id' => $penilaian->id,
                            'alternatif_id' => $penilaian->alternatif_id,
                            'alternatif_nama' => $penilaian->alternatif->nama ?? 'Unknown',
                            'kriteria_id' => $penilaian->kriteria_id,
                            'subkriteria_id' => $penilaian->subkriteria_id,
                            'nilai_lama' => $oldNilai,
                            'nilai_baru' => $newNilai
                        ]);
                    } else {
                        $errors[] = "Penilaian ID {$penilaian->id} tidak memiliki subkriteria yang valid";
                        
                        Log::warning('Penilaian tanpa subkriteria valid', [
                            'penilaian_id' => $penilaian->id,
                            'alternatif_id' => $penilaian->alternatif_id,
                            'kriteria_id' => $penilaian->kriteria_id,
                            'subkriteria_id' => $penilaian->subkriteria_id
                        ]);
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error updating penilaian ID {$penilaian->id}: " . $e->getMessage();
                    
                    Log::error('Gagal update nilai penilaian via service', [
                        'penilaian_id' => $penilaian->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Berhasil mengupdate {$updated} data penilaian" . 
                           (count($errors) > 0 ? " dengan " . count($errors) . " error" : ""),
                'updated' => $updated,
                'errors' => $errors,
                'total_processed' => $penilaians->count()
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal update nilai penilaian service', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'updated' => 0,
                'errors' => [$e->getMessage()]
            ];
        }
    }

    /**
     * Sinkronisasi nilai penilaian untuk alternatif tertentu
     * 
     * @param int $alternatifId
     * @return array
     */
    public function sinkronisasiNilaiAlternatif($alternatifId)
    {
        return $this->updateNilaiPenilaian($alternatifId, true);
    }

    /**
     * Validasi konsistensi data penilaian
     * 
     * @return array
     */
    public function validasiKonsistensiPenilaian()
    {
        try {
            // Cek penilaian yang tidak memiliki nilai
            $penilaianTanpaNilai = Penilaian::whereNull('nilai')
                ->with(['alternatif', 'kriteria', 'subkriteria'])
                ->get();

            // Cek penilaian yang nilai tidak sesuai dengan subkriteria
            $penilaianTidakSesuai = Penilaian::whereNotNull('nilai')
                ->whereHas('subkriteria', function($query) {
                    $query->whereColumn('penilaians.nilai', '!=', 'sub_kriterias.nilai');
                })
                ->with(['alternatif', 'kriteria', 'subkriteria'])
                ->get();

            // Cek penilaian yang tidak memiliki subkriteria
            $penilaianTanpaSubkriteria = Penilaian::whereNull('subkriteria_id')
                ->orWhereDoesntHave('subkriteria')
                ->with(['alternatif', 'kriteria'])
                ->get();

            return [
                'success' => true,
                'tanpa_nilai' => [
                    'count' => $penilaianTanpaNilai->count(),
                    'data' => $penilaianTanpaNilai->map(function($p) {
                        return [
                            'id' => $p->id,
                            'alternatif' => $p->alternatif->nama ?? 'Unknown',
                            'kriteria' => $p->kriteria->nama_kriteria ?? 'Unknown',
                            'subkriteria' => $p->subkriteria->nama_sub_kriteria ?? 'Unknown'
                        ];
                    })
                ],
                'tidak_sesuai' => [
                    'count' => $penilaianTidakSesuai->count(),
                    'data' => $penilaianTidakSesuai->map(function($p) {
                        return [
                            'id' => $p->id,
                            'alternatif' => $p->alternatif->nama ?? 'Unknown',
                            'kriteria' => $p->kriteria->nama_kriteria ?? 'Unknown',
                            'nilai_penilaian' => $p->nilai,
                            'nilai_subkriteria' => $p->subkriteria->nilai ?? 'Unknown'
                        ];
                    })
                ],
                'tanpa_subkriteria' => [
                    'count' => $penilaianTanpaSubkriteria->count(),
                    'data' => $penilaianTanpaSubkriteria->map(function($p) {
                        return [
                            'id' => $p->id,
                            'alternatif' => $p->alternatif->nama ?? 'Unknown',
                            'kriteria' => $p->kriteria->nama_kriteria ?? 'Unknown',
                            'subkriteria_id' => $p->subkriteria_id
                        ];
                    })
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Gagal validasi konsistensi penilaian', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ];
        }
    }
}