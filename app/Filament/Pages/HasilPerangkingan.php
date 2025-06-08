<?php

namespace App\Filament\Pages;

use App\Models\HasilPenilaian;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

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
