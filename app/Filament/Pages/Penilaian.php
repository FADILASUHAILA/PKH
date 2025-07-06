<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\BioData;
use App\Models\Indikasi;
use App\Models\Penilaian as ModelsPenilaian;
use App\Models\PenilaianHeader;
use App\Services\PrometheeService;
use Filament\Pages\Page;
use Illuminate\Contracts\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class Penilaian extends Page
{
    use HasPageShield;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static string $view = 'filament.pages.penilaian';
    protected static ?string $title = 'Penilaian';
    protected static ?string $navigationGroup = 'Perhitungan';
    public $alternatifs;
    public $penilaians;
    public $bioDatas;

    public function mount()
    {
        $this->alternatifs = Alternatif::with(['biodata', 'desa', 'penilaian'])->get();
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('penilaian');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('hitungPromethee')
                ->label('Hitung PROMETHEE')
                ->icon('heroicon-o-calculator')
                ->color('primary')
                ->action(function (PrometheeService $prometheeService) {
                    try {
                        $results = $prometheeService->calculate();
                        dd($results);

                        Notification::make()
                            ->title('Perhitungan PROMETHEE Berhasil')
                            ->success()
                            ->send();

                        return redirect()->route('filament.admin.pages.hasil-penilaian', [
                            'results' => json_encode($results)
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
                ->modalButton('Ya, Hitung Sekarang'),
        ];
    }

    // Method untuk handle delete dari form
    public function delete($alternatifId)
    {
        try {
            $alternatif = Alternatif::findOrFail($alternatifId);

            // Start transaction to ensure data consistency
            DB::beginTransaction();

            // Delete all related data
            $alternatif->penilaian()->delete(); // Delete penilaian details
            $alternatif->indikasis()->delete(); // Delete indikasi data
            $alternatif->hasilPenilaian()->delete(); // Delete indikasi data

            // Find and delete penilaian headers for this alternatif
            PenilaianHeader::where('alternatif_id', $alternatifId)->delete();

            DB::commit();

            Notification::make()
                ->title('Data berhasil dihapus')
                ->success()
                ->body('Semua data penilaian, indikasi, dan header penilaian untuk alternatif ini telah dihapus.')
                ->send();

            return redirect()->route('filament.admin.pages.penilaian');
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Gagal menghapus data')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();

            return back();
        }
    }
}
