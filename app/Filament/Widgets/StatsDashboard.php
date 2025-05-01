<?php

namespace App\Filament\Widgets;

use App\Models\Kriteria;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {   
        $countKriteria = Kriteria::count();
        return [
            Stat::make(label:'jumlah Kriteria', value:'Kriteria'),
            
        ];
    }
}
