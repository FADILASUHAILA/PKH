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
        $countAlternatif = Alternatif::count();
        $countDesa = Desa::count();

        return [
            Stat::make('Jumlah Kriteria', $countKriteria)
                ->description('Total kriteria penilaian')
                ->descriptionIcon('heroicon-o-scale')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 border border-blue-200 dark:border-blue-700 rounded-lg',
                ]),
                
            Stat::make('Jumlah Alternatif', $countAlternatif)
                ->description('Total alternatif yang dinilai')
                ->descriptionIcon('heroicon-o-list-bullet')
                ->color('success')
                ->chart([15, 4, 10, 2, 12, 4, 12])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 border border-green-200 dark:border-green-700 rounded-lg',
                ]),
                
            Stat::make('Jumlah Desa', $countDesa)
                ->description('Total desa yang terdaftar')
                ->descriptionIcon('heroicon-o-map')
                ->color('warning')
                ->chart([3, 5, 8, 2, 10, 4, 12])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:shadow-lg transition-shadow bg-gradient-to-r from-amber-50 to-amber-100 dark:from-amber-900 dark:to-amber-800 border border-amber-200 dark:border-amber-700 rounded-lg',
                ]),
        ];
    }
}