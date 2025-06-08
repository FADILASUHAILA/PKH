<?php

namespace App\Filament\Pages\Penilaian;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Contracts\Support\Htmlable;

class HasilPenilaian extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.penilaian.hasil-penilaian';
    protected static ?string $navigationGroup = 'Perhitungan';
    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    public array $results = [];
    public array $decisionMatrix = [];
    public array $preferenceMatrix = [];
    public array $leavingFlow = [];
    public array $enteringFlow = [];
    public array $netFlow = [];
    public array $ranking = [];
    public $alternatifs;
    public $kriterias;
    public $hasilPenilaian;
    public function scopeOnlyEvaluated($query)
    {
        return $query->whereNotNull('net_flow');
    }

    public function mount(): void
    {
        // Ambil data dari database
        $this->hasilPenilaian = \App\Models\HasilPenilaian::with(['alternatif', 'penilaian'])
            ->latest()
            ->get()
            ->groupBy('penilaian_id')
            ->first(); // Ambil hasil penilaian terbaru

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
            // $this->ranking = HasilPenilaian::onlyEvaluated()->byRanking()->get();

            // dd($this->decisionMatrix);
        }
    }
}
