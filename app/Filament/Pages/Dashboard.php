<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\Desa;
use App\Models\Kriteria;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public $totalAlternatif;
    public $totalDesa;
    public $totalKriteria;
    public $latestAlternatifs;
    public $alternatifPerDesa;
    public $monthlyGrowth;

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
    }
}
