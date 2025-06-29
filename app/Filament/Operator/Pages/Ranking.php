<?php

namespace App\Filament\Operator\Pages;

use Filament\Pages\Page;
use App\Models\HasilPenilaian;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Dompdf\Dompdf;
use Dompdf\Options;

class Ranking extends Page
{
    use HasPageShield;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.operator.pages.ranking';

    protected static ?int $navigationSort = 3;

    public $hasilPenilaian;
    public $alternatifs;
    public $rankingData;

    public function mount()
    {
        $this->hasilPenilaian = HasilPenilaian::with(['alternatif.biodata', 'penilaian'])
            ->latest()
            ->get()
            ->groupBy('penilaian_id')
            ->first();

        if ($this->hasilPenilaian) {
            $this->alternatifs = $this->hasilPenilaian->pluck('alternatif')->unique();

            $this->rankingData = $this->hasilPenilaian->sortBy('ranking')->map(function ($item) {
                return [
                    'nama' => $item->alternatif->nama,
                    'nik' => $item->alternatif->biodata->nik ?? '-',
                    'alamat' => $item->alternatif->biodata->alamat ?? '-',
                    'no_hp' => $item->alternatif->biodata->no_hp ?? '-',
                    'net_flow' => $item->net_flow,
                    'ranking' => $item->ranking
                ];
            });

            // Simpan rankingData ke session
            session(['rankingData' => $this->rankingData]);
        }
    }

    protected function getViewData(): array
    {
        return [
            'rankingData' => $this->rankingData,
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

    public function downloadPdf()
    {
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);
        // Ambil data dari session
        $rankingData = session('rankingData');
        if (!$rankingData) {
            return abort(404, 'Data tidak ditemukan');
        }
        $html = view('filament.pages.pdf-hasil-perangkingan', [
            'rankingData' => $rankingData
        ])->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'potrait');
        $dompdf->render();
        $dompdf->stream('hasil_perangkingan.pdf');
    }
}
