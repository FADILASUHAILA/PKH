<?php

namespace App\Filament\Pages;

use App\Models\HasilPenilaian;
use Filament\Pages\Page;

class HasilPerangkingan extends Page
{
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
        ];
    }
}
