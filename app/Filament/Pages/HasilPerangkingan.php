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
        $this->hasilPenilaian = HasilPenilaian::with(['alternatif', 'penilaian'])
            ->latest()
            ->get()
            ->groupBy('penilaian_id')
            ->first();

        if ($this->hasilPenilaian) {
            $this->alternatifs = $this->hasilPenilaian->pluck('alternatif')->unique();
            
            $this->rankingData = $this->hasilPenilaian->sortBy('ranking')->map(function ($item) {
                return [
                    'nama' => $item->alternatif->nama,
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
        ];
    }

    private function getChartData(): array
    {
        if (!$this->rankingData) {
            return [
                'labels' => [],
                'data' => [],
                'colors' => []
            ];
        }

        $labels = [];
        $data = [];
        $colors = [];

        // Generate colors for each bar
        $colorPalette = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#F97316', '#84CC16', '#EC4899', '#6B7280'
        ];

        foreach ($this->rankingData as $index => $item) {
            $labels[] = $item['nama'];
            $data[] = round($item['net_flow'], 4);
            $colors[] = $colorPalette[$index % count($colorPalette)];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors
        ];
    }
}