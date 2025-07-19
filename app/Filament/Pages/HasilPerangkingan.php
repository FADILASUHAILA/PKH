<?php

namespace App\Filament\Pages;

use App\Models\HasilPenilaian;
use App\Models\Desa;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Dompdf\Dompdf;
use Dompdf\Options;

class HasilPerangkingan extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.hasil-perangkingan';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?int $navigationSort = 3;

    public $hasilPenilaian;
    public $alternatifs;
    public $rankingData;
    public $rankingPerDesa;

    public function mount()
    {
        $this->hasilPenilaian = HasilPenilaian::with(['alternatif.biodata', 'alternatif.desa', 'penilaian'])
            ->latest()
            ->get()
            ->groupBy('penilaian_id')
            ->first();

        if ($this->hasilPenilaian) {
            $this->alternatifs = $this->hasilPenilaian->pluck('alternatif')->unique();

            // Data ranking semua alternatif (global ranking)
            $this->rankingData = $this->hasilPenilaian->sortBy('ranking')->map(function ($item) {
                return [
                    'nama' => $item->alternatif->nama,
                    'nik' => $item->alternatif->biodata->nik ?? '-',
                    'no_hp' => $item->alternatif->biodata->no_hp ?? '-',
                    'desa' => $item->alternatif->desa->nama_desa ?? '-',
                    'desa_id' => $item->alternatif->desa_id ?? null,
                    'net_flow' => $item->net_flow,
                    'ranking' => $item->ranking
                ];
            });

            // Data ranking per desa (dengan ranking lokal per desa)
            $this->rankingPerDesa = $this->hasilPenilaian
                ->sortBy('ranking')
                ->groupBy('alternatif.desa_id')
                ->map(function ($items, $desaId) {
                    return $items->take(8)->map(function ($item, $index) {
                        return [
                            'nama' => $item->alternatif->nama,
                            'nik' => $item->alternatif->biodata->nik ?? '-',
                            'no_hp' => $item->alternatif->biodata->no_hp ?? '-',
                            'desa' => $item->alternatif->desa->nama_desa ?? '-',
                            'desa_id' => $item->alternatif->desa_id ?? null,
                            'net_flow' => $item->net_flow,
                            'global_ranking' => $item->ranking, // ranking global
                            'local_ranking' => $index + 1 // ranking lokal per desa
                        ];
                    });
                });

            session([
                'rankingData' => $this->rankingData,
                'rankingPerDesa' => $this->rankingPerDesa
            ]);
        }
    }

    protected function getViewData(): array
    {
        return [
            'rankingData' => $this->rankingData,
            'rankingPerDesa' => $this->rankingPerDesa,
            'chartData' => $this->getChartData(),
            'hasBioData' => $this->rankingData && $this->rankingData->first() && isset($this->rankingData->first()['nik'])
        ];
    }

    private function getChartData(): array
    {
        if (!$this->rankingData) {
            return [
                'labels' => [],
                'data' => [],
                'colors' => [],
                'niks' => []
            ];
        }

        $labels = [];
        $data = [];
        $colors = [];
        $niks = [];

        $colorPalette = [
            '#3B82F6',
            '#EF4444',
            '#10B981',
            '#F59E0B',
            '#8B5CF6',
            '#06B6D4',
            '#F97316',
            '#84CC16',
            '#EC4899',
            '#6B7280'
        ];

        foreach ($this->rankingData as $index => $item) {
            $labels[] = $item['nama'];
            $data[] = round($item['net_flow'], 4);
            $colors[] = $colorPalette[$index % count($colorPalette)];
            $niks[] = $item['nik'] ?? '';
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'niks' => $niks
        ];
    }

    public function downloadAllPdf()
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $rankingPerDesa = session('rankingPerDesa');
        if (!$rankingPerDesa) {
            return abort(404, 'Data tidak ditemukan');
        }

        $html = view('filament.pages.pdf-hasil-perangkingan', [
            'rankingPerdesa' => $rankingPerDesa,
            'title' => 'Laporan Hasil Perangkingan PKH - Semua Desa',
            'tanggal' => now()->format('d/m/Y H:i:s')
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'hasil_perangkingan_semua_desa_' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function downloadPerDesaPdf()
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $rankingPerDesa = $this->rankingPerDesa;
        if (!$rankingPerDesa) {
            return abort(404, 'Data perangkingan per desa tidak ditemukan');
        }

        $html = view('filament.pages.pdf-hasil-perangkingan-per-desa', [
            'rankingPerdesa' => $rankingPerDesa,
            'title' => 'Laporan Hasil Perangkingan PKH - Per Desa',
            'tanggal' => now()->format('d/m/Y H:i:s'),
            'totalDesa' => count($rankingPerDesa),
            'totalPenerima' => $rankingPerDesa->flatten()->count()
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'hasil_perangkingan_per_desa_' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function downloadDesaPdf($desaId)
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $rankingPerDesa = $this->rankingPerDesa;
        if (!$rankingPerDesa || !isset($rankingPerDesa[$desaId])) {
            return abort(404, 'Data desa tidak ditemukan');
        }

        $desaData = $rankingPerDesa[$desaId];
        $namaDesaData = $desaData->first();
        $namaDesa = $namaDesaData['desa'] ?? 'Tidak Diketahui';

        $html = view('filament.pages.pdf-hasil-perangkingan-desa', [
            'desaData' => $desaData,
            'namaDesa' => $namaDesa,
            'title' => 'Laporan Hasil Perangkingan PKH - Desa ' . $namaDesa,
            'tanggal' => now()->format('d/m/Y H:i:s'),
            'totalPenerima' => $desaData->count()
        ])->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'hasil_perangkingan_desa_' . str_replace(' ', '_', strtolower($namaDesa)) . '_' . now()->format('Y-m-d') . '.pdf'
        );
    }
}