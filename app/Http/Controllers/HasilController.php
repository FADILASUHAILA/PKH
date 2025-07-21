<?php

namespace App\Http\Controllers;

use App\Models\HasilPenilaian;
use App\Models\Desa;
use App\Models\Alternatif;
use App\Models\BioData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    /**
     * Menampilkan halaman hasil dengan status kelulusan
     */
    public function index()
    {
        // Ambil semua hasil penilaian dengan relasi yang diperlukan
        $hasilPenilaian = HasilPenilaian::with([
            'alternatif.biodata', 
            'alternatif.desa'
        ])
        ->whereHas('alternatif.biodata') // Pastikan ada biodata
        ->get();

        // Kelompokkan berdasarkan desa dan urutkan berdasarkan net_flow per desa
        $hasilPerDesa = $hasilPenilaian->groupBy('alternatif.desa.nama_desa')
            ->map(function ($items) {
                return $items->sortByDesc('net_flow')->values();
            });

        // Statistik
        $totalCalonPenerima = $hasilPenilaian->count();
        $totalLolos = $hasilPenilaian->where('status_rekomendasi', 'Lolos Rekomendasi')->count();
        $totalTidakLolos = $hasilPenilaian->where('status_rekomendasi', 'Tidak Lolos Rekomendasi')->count();
        $totalDesa = $hasilPerDesa->count();
        $kuotaMaksimal = $totalDesa * 8; // 8 orang per desa

        return view('hasil.index', compact(
            'hasilPerDesa', 
            'totalCalonPenerima', 
            'totalLolos', 
            'totalTidakLolos',
            'totalDesa',
            'kuotaMaksimal'
        ));
    }

    /**
     * Mencari calon penerima berdasarkan NIK dan menampilkan status kelulusannya
     */
    public function cariByNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16'
        ], [
            'nik.required' => 'NIK harus diisi',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka'
        ]);

        // Cari biodata berdasarkan NIK
        $biodata = BioData::where('nik', $request->nik)->first();

        if (!$biodata) {
            return back()->with('error', 'Data calon penerima dengan NIK ' . $request->nik . ' tidak ditemukan dalam sistem.');
        }

        // Cari alternatif dan hasil penilaian
        $alternatif = Alternatif::with(['desa', 'hasilPenilaian'])
            ->where('id', $biodata->alternatif_id)
            ->first();

        if (!$alternatif) {
            return back()->with('error', 'Data alternatif untuk NIK ' . $request->nik . ' tidak ditemukan.');
        }

        $hasilPenilaian = $alternatif->hasilPenilaian->first();

        if (!$hasilPenilaian) {
            return back()->with('error', 'Data hasil penilaian untuk NIK ' . $request->nik . ' belum tersedia. Silakan lakukan penilaian terlebih dahulu.');
        }

        // Hitung posisi dalam desa berdasarkan net_flow
        $posisiDesa = HasilPenilaian::with('alternatif')
            ->whereHas('alternatif', function($query) use ($alternatif) {
                $query->where('desa_id', $alternatif->desa_id);
            })
            ->whereHas('alternatif.biodata')
            ->where('net_flow', '>', $hasilPenilaian->net_flow)
            ->count() + 1;

        // Hitung total calon di desa yang sama
        $totalCalonDesa = HasilPenilaian::with('alternatif')
            ->whereHas('alternatif', function($query) use ($alternatif) {
                $query->where('desa_id', $alternatif->desa_id);
            })
            ->whereHas('alternatif.biodata')
            ->count();

        // Tentukan apakah masuk 8 besar
        $masuk8Besar = $posisiDesa <= 8;

        // Data untuk view
        $dataCalonPenerima = [
            'biodata' => $biodata,
            'alternatif' => $alternatif,
            'hasil_penilaian' => $hasilPenilaian,
            'posisi_desa' => $posisiDesa,
            'total_calon_desa' => $totalCalonDesa,
            'masuk_8_besar' => $masuk8Besar,
            'status_prediksi' => $masuk8Besar ? 'Lolos Rekomendasi' : 'Tidak Lolos Rekomendasi'
        ];

        return view('hasil.pencarian-nik', compact('dataCalonPenerima'));
    }

    /**
     * Menetapkan status kelulusan berdasarkan kuota per desa
     * Setiap desa maksimal 8 orang yang lolos rekomendasi
     */
    public function penetapanStatus()
    {
        try {
            DB::beginTransaction();

            // Reset semua status terlebih dahulu
            HasilPenilaian::query()->update(['status_rekomendasi' => null]);

            // Ambil semua desa yang memiliki calon penerima
            $desaList = Desa::whereHas('alternatifs.hasilPenilaian')
                ->get();

            $totalDiproses = 0;
            $totalLolos = 0;
            $totalTidakLolos = 0;
            $kuotaPerDesa = 8; // Kuota maksimal per desa
            $detailProses = [];

            foreach ($desaList as $desa) {
                // Ambil hasil penilaian untuk desa ini, diurutkan berdasarkan net_flow tertinggi
                $hasilPenilaianDesa = HasilPenilaian::with(['alternatif.biodata', 'alternatif.desa'])
                    ->whereHas('alternatif', function($query) use ($desa) {
                        $query->where('desa_id', $desa->id);
                    })
                    ->whereHas('alternatif.biodata') // Pastikan ada biodata
                    ->orderBy('net_flow', 'desc') // Urutkan berdasarkan net_flow tertinggi
                    ->orderBy('ranking', 'asc')    // Jika net_flow sama, urutkan berdasarkan ranking
                    ->get();

                $jumlahCalonDesa = $hasilPenilaianDesa->count();
                $lolosDesa = 0;
                $tidakLolosDesa = 0;

                // Proses penetapan status untuk setiap calon di desa ini
                foreach ($hasilPenilaianDesa as $index => $hasil) {
                    $posisiDesa = $index + 1; // Posisi dalam desa (1, 2, 3, ...)
                    
                    if ($posisiDesa <= $kuotaPerDesa) {
                        // 8 teratas berdasarkan net_flow mendapat status "Lolos Rekomendasi"
                        $hasil->update(['status_rekomendasi' => 'Lolos Rekomendasi']);
                        $totalLolos++;
                        $lolosDesa++;
                    } else {
                        // Sisanya mendapat status "Tidak Lolos Rekomendasi"
                        $hasil->update(['status_rekomendasi' => 'Tidak Lolos Rekomendasi']);
                        $totalTidakLolos++;
                        $tidakLolosDesa++;
                    }
                    
                    $totalDiproses++;
                }

                // Simpan detail proses per desa
                $detailProses[] = [
                    'desa' => $desa->nama_desa,
                    'total_calon' => $jumlahCalonDesa,
                    'lolos' => $lolosDesa,
                    'tidak_lolos' => $tidakLolosDesa,
                    'kuota_terpakai' => min($jumlahCalonDesa, $kuotaPerDesa)
                ];
            }

            DB::commit();

            // Hitung total desa yang diproses
            $totalDesa = count($desaList);
            $totalKuotaKeseluruhan = $totalDesa * $kuotaPerDesa;

            // Buat pesan sukses yang detail
            $pesanSukses = "✅ Status kelulusan berhasil ditetapkan!\n\n";
            $pesanSukses .= "📊 RINGKASAN HASIL:\n";
            $pesanSukses .= "• Total Desa: {$totalDesa}\n";
            $pesanSukses .= "• Total Calon Penerima: {$totalDiproses}\n";
            $pesanSukses .= "• Kuota Maksimal Keseluruhan: {$totalKuotaKeseluruhan} orang ({$totalDesa} desa × {$kuotaPerDesa} orang)\n";
            $pesanSukses .= "• ✅ Lolos Rekomendasi: {$totalLolos} orang\n";
            $pesanSukses .= "• ❌ Tidak Lolos: {$totalTidakLolos} orang\n\n";
            
            $pesanSukses .= "🏘️ DETAIL PER DESA:\n";
            foreach ($detailProses as $detail) {
                $pesanSukses .= "• {$detail['desa']}: {$detail['lolos']}/{$detail['total_calon']} lolos\n";
            }

            return redirect()->route('hasil.index')->with('success', $pesanSukses);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()->with('error', 
                'Terjadi kesalahan saat menetapkan status: ' . $e->getMessage()
            );
        }
    }

    /**
     * Menampilkan detail hasil per desa
     */
    public function detailDesa($desaId)
    {
        $desa = Desa::findOrFail($desaId);
        
        $hasilPenilaian = HasilPenilaian::with([
            'alternatif.biodata', 
            'alternatif.desa'
        ])
        ->whereHas('alternatif', function($query) use ($desaId) {
            $query->where('desa_id', $desaId);
        })
        ->whereHas('alternatif.biodata')
        ->orderBy('net_flow', 'desc') // Urutkan berdasarkan net_flow tertinggi
        ->orderBy('ranking', 'asc')   // Jika net_flow sama, urutkan berdasarkan ranking
        ->get();

        $totalCalon = $hasilPenilaian->count();
        $totalLolos = $hasilPenilaian->where('status_rekomendasi', 'Lolos Rekomendasi')->count();
        $kuotaDesa = 8;

        return view('hasil.detail-desa', compact(
            'desa', 
            'hasilPenilaian', 
            'totalCalon', 
            'totalLolos',
            'kuotaDesa'
        ));
    }

    /**
     * Export hasil ke CSV
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $desaId = $request->get('desa');
        
        $query = HasilPenilaian::with([
            'alternatif.biodata', 
            'alternatif.desa'
        ])->whereHas('alternatif.biodata');

        // Filter per desa jika diminta
        if ($desaId) {
            $query->whereHas('alternatif', function($q) use ($desaId) {
                $q->where('desa_id', $desaId);
            });
        }

        $hasilPenilaian = $query->get()->groupBy('alternatif.desa.nama_desa')
            ->map(function ($items) {
                return $items->sortByDesc('net_flow')->values();
            })->flatten();

        return $this->exportToCsv($hasilPenilaian, $desaId);
    }

    private function exportToCsv($data, $desaId = null)
    {
        $filename = 'hasil_penilaian_';
        if ($desaId) {
            $desa = Desa::find($desaId);
            $filename .= strtolower(str_replace(' ', '_', $desa->nama_desa ?? 'desa')) . '_';
        }
        $filename .= date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // BOM untuk UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header CSV
            fputcsv($file, [
                'No',
                'NIK',
                'Nama Lengkap',
                'Desa',
                'Nilai Net Flow',
                'Ranking Global',
                'Status Rekomendasi'
            ]);

            // Data
            $no = 1;
            foreach ($data as $hasil) {
                fputcsv($file, [
                    $no++,
                    $hasil->alternatif->biodata->nik ?? '-',
                    $hasil->alternatif->biodata->nama ?? $hasil->alternatif->nama,
                    $hasil->alternatif->desa->nama_desa ?? '-',
                    number_format($hasil->net_flow, 6),
                    $hasil->ranking,
                    $hasil->status_rekomendasi ?? 'Belum Ditetapkan'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Menampilkan ringkasan statistik
     */
    public function statistik()
    {
        $hasilPenilaian = HasilPenilaian::with([
            'alternatif.biodata', 
            'alternatif.desa'
        ])->whereHas('alternatif.biodata')->get();

        $statistikPerDesa = $hasilPenilaian->groupBy('alternatif.desa.nama_desa')
            ->map(function ($items, $namaDesa) {
                $totalCalon = $items->count();
                $lolos = $items->where('status_rekomendasi', 'Lolos Rekomendasi')->count();
                $tidakLolos = $items->where('status_rekomendasi', 'Tidak Lolos Rekomendasi')->count();
                
                return [
                    'nama_desa' => $namaDesa,
                    'total_calon' => $totalCalon,
                    'lolos' => $lolos,
                    'tidak_lolos' => $tidakLolos,
                    'persentase_lolos' => $totalCalon > 0 ? round(($lolos / $totalCalon) * 100, 2) : 0,
                    'kuota_terpakai' => min($totalCalon, 8),
                    'efisiensi_kuota' => $totalCalon >= 8 ? 100 : round(($totalCalon / 8) * 100, 2)
                ];
            });

        return view('hasil.statistik', compact('statistikPerDesa'));
    }

    /**
     * API endpoint untuk pencarian cepat NIK (untuk AJAX)
     */
    public function apiCariNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16'
        ]);

        $biodata = BioData::with(['alternatif.desa', 'alternatif.hasilPenilaian'])
            ->where('nik', $request->nik)
            ->first();

        if (!$biodata || !$biodata->alternatif || !$biodata->alternatif->hasilPenilaian->first()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $alternatif = $biodata->alternatif;
        $hasilPenilaian = $alternatif->hasilPenilaian->first();

        // Hitung posisi dalam desa
        $posisiDesa = HasilPenilaian::with('alternatif')
            ->whereHas('alternatif', function($query) use ($alternatif) {
                $query->where('desa_id', $alternatif->desa_id);
            })
            ->whereHas('alternatif.biodata')
            ->where('net_flow', '>', $hasilPenilaian->net_flow)
            ->count() + 1;

        return response()->json([
            'success' => true,
            'data' => [
                'nik' => $biodata->nik,
                'nama' => $biodata->nama,
                'desa' => $alternatif->desa->nama_desa,
                'net_flow' => $hasilPenilaian->net_flow,
                'ranking_global' => $hasilPenilaian->ranking,
                'posisi_desa' => $posisiDesa,
                'status_rekomendasi' => $hasilPenilaian->status_rekomendasi,
                'masuk_8_besar' => $posisiDesa <= 8
            ]
        ]);
    }
}