<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\Penilaian as ModelsPenilaian;
use App\Services\PrometheeService;
use Filament\Pages\Page;
use Illuminate\Contracts\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Notifications\Notification;


class Penilaian extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static string $view = 'filament.pages.penilaian';

    protected static ?string $navigationGroup = 'Perhitungan';

    public $alternatifs;
    public $penilaians;

    public function mount()
    {
        $this->alternatifs = Alternatif::all();
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('penilaian');
    }

    protected function getHeaderActions(): array
    {
        return [Action::make('hitungPromethee')
            ->label('Hitung PROMETHEE')
            ->icon('heroicon-o-calculator')
            ->color('primary')
            ->action(function (PrometheeService $prometheeService) {
                try {
                    $results = $prometheeService->calculate();
                    // dd($results);

                    // Simpan hasil ke database jika diperlukan
                    // foreach ($results as $result) {
                    //     // Contoh penyimpanan hasil
                    //     $resultModel = \App\Models\HasilPromethee::updateOrCreate(
                    //         ['alternatif_id' => $result['alternatif']->id],
                    //         [
                    //             'leaving_flow' => $result['leaving'],
                    //             'entering_flow' => $result['entering'],
                    //             'net_flow' => $result['net'],
                    //             'ranking' => array_search($result, $results) + 1
                    //         ]
                    //     );
                    // }

                    Notification::make()
                        ->title('Perhitungan PROMETHEE Berhasil')
                        ->success()
                        ->send();

                    // dd($results['preferenceMatrix']);

                    // return redirect()->route('filament.admin.pages.promethee-result');
                    return redirect()->route('filament.admin.pages.hasil-penilaian', [
                        'results' => json_encode($results) // Encode sebagai JSON
                    ]);
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Gagal Menghitung PROMETHEE')
                        ->body($e->getMessage())
                        ->danger()
                        ->send();
                }
            })
            ->requiresConfirmation()
            ->modalHeading('Hitung PROMETHEE')
            ->modalSubheading('Apakah Anda yakin ingin menjalankan perhitungan PROMETHEE?')
            ->modalButton('Ya, Hitung Sekarang')];
    }
}
