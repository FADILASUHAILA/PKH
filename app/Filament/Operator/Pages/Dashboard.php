<?php

namespace App\Filament\Operator\Pages;

use App\Models\Alternatif;
use App\Models\Desa;
use App\Models\HasilPenilaian;
use App\Models\Kriteria;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.operator.pages.dashboard';

    public $totalAlternatif;
    public $totalDesa;
    public $totalKriteria;
    public $latestAlternatifs;
    public $alternatifPerDesa;
    public $monthlyGrowth;
    public $hasilPenilaian;
    public $alternatifs;
    public $rankingData;

    public function mount()
    {
        $this->totalAlternatif = Alternatif::count();
        $this->totalDesa = Desa::count();
        $this->totalKriteria = Kriteria::count();
        $this->latestAlternatifs = Alternatif::with('desa')->latest()->take(5)->get();

        // Data untuk chart alternatif per desa
        $this->alternatifPerDesa = Desa::withCount('alternatifs')
            ->orderBy('alternatifs_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($desa) {
                return [
                    'desa' => $desa->nama_desa,
                    'total' => $desa->alternatifs_count
                ];
            });

        // Data untuk pertumbuhan bulanan
        $this->monthlyGrowth = Alternatif::selectRaw('
        EXTRACT(YEAR FROM created_at) as year,
        EXTRACT(MONTH FROM created_at) as month,
        COUNT(*) as total
    ')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

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
}
