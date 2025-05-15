<?php

namespace App\Filament\Widgets;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Desa;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $countKriteria = Kriteria::count();
        $countalternatif = Alternatif::count();
        $countdesa = desa::count();
        return [
            Stat::make(label: 'Jumlah Kriteria', value: $countKriteria),
            stat::make(label: 'jumlah alternatif', value: $countalternatif),
            stat::make(label: 'jumlah desa', value:$countdesa)

        ];
    }
}
