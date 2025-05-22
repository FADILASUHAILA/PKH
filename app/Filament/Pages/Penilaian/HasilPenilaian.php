<?php

namespace App\Filament\Pages\Penilaian;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class HasilPenilaian extends Page
{


    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.penilaian.hasil-penilaian';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?string $title = 'Hasil Perhitungan PROMETHEE';

    public array $results = [];
    public array $decisionMatrix = [];
    public array $preferenceMatrix = [];
    public array $leavingFlow = [];
    public array $enteringFlow = [];
    public array $netFlow = [];
    public array $ranking = [];
    public $alternatifs;
    public $kriterias;

    public function mount(): void
    {
        if ($results = request('results')) {
            $results = json_decode($results, true);

            $this->results = $results;
            $this->decisionMatrix = $results['decisionMatrix'] ?? [];
            $this->preferenceMatrix = $results['preferenceMatrix'] ?? [];
            $this->leavingFlow = $results['leavingFlow'] ?? [];
            $this->enteringFlow = $results['enteringFlow'] ?? [];
            $this->netFlow = $results['netFlow'] ?? [];
            $this->ranking = $results['ranking'] ?? [];

            // Load models fresh from database
            $this->alternatifs = \App\Models\Alternatif::findMany($results['alternatif_ids'] ?? []);
            $this->kriterias = \App\Models\Kriteria::findMany($results['kriteria_ids'] ?? []);

            // dd($this->decisionMatrix);
        }
    }
}
