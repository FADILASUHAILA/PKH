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
        // Prioritas: ambil dari parameter request jika ada
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
        } else {
            // Jika tidak ada parameter, ambil dari database
            $this->loadFromDatabase();
        }
    }

    private function loadFromDatabase(): void
    {
        // Ambil hasil penilaian terbaru dari database
        $this->hasilPenilaian = \App\Models\HasilPenilaian::with(['alternatif.biodata', 'header'])
            ->latest()
            ->get();

        if ($this->hasilPenilaian->isNotEmpty()) {
            // Ambil data untuk tampilan
            $this->alternatifs = $this->hasilPenilaian->pluck('alternatif')->unique('id');
            $this->kriterias = \App\Models\Kriteria::all();

            // Rekonstruksi data dari database
            foreach ($this->hasilPenilaian as $hasil) {
                $altId = $hasil->alternatif_id;
                
                $this->decisionMatrix[$altId] = $hasil->decision_matrix ?? [];
                $this->preferenceMatrix[$altId] = $hasil->preference_matrix ?? [];
                $this->leavingFlow[$altId] = $hasil->leaving_flow ?? 0;
                $this->enteringFlow[$altId] = $hasil->entering_flow ?? 0;
                $this->netFlow[$altId] = $hasil->net_flow ?? 0;
                $this->ranking[$altId] = $hasil->ranking ?? 999;
            }

            // Set results array untuk konsistensi
            $this->results = [
                'decisionMatrix' => $this->decisionMatrix,
                'preferenceMatrix' => $this->preferenceMatrix,
                'leavingFlow' => $this->leavingFlow,
                'enteringFlow' => $this->enteringFlow,
                'netFlow' => $this->netFlow,
                'ranking' => $this->ranking,
                'alternatif_ids' => $this->alternatifs->pluck('id')->toArray(),
                'kriteria_ids' => $this->kriterias->pluck('id')->toArray()
            ];
        }
    }
}
