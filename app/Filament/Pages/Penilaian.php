<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\BioData;
use App\Models\Indikasi;
use App\Models\Penilaian as ModelsPenilaian;
use App\Models\PenilaianHeader;
use App\Services\PrometheeService;
use App\Services\ExcelImportService;
use Filament\Pages\Page;
use Illuminate\Contracts\Database\Eloquent\Builder;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            Action::make('downloadTemplate')
                ->label('Download Template Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(function (ExcelImportService $importService) {
                    try {
                        $spreadsheet = $importService->generateTemplate();
                        $writer = new Xlsx($spreadsheet);
                        
                        $fileName = 'template_import_penilaian_' . date('Y-m-d_H-i-s') . '.xlsx';
                        
                        return new StreamedResponse(function() use ($writer) {
                            $writer->save('php://output');
                        }, 200, [
                            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                            'Cache-Control' => 'max-age=0',
                        ]);
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Download Template')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('importExcel')
                ->label('Import Excel')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    FileUpload::make('excel_file')
                        ->label('File Excel')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required()
                        ->maxSize(5120) // 5MB
                        ->helperText('Format file: .xlsx atau .xls (maksimal 5MB)')
                        ->disk('local')
                        ->directory('imports')
                ])
                ->action(function (array $data, ExcelImportService $importService) {
                    try {
                        $filePath = Storage::disk('local')->path($data['excel_file']);
                        
                        $result = $importService->importFromExcel($filePath);
                        
                        // Clean up uploaded file
                        Storage::disk('local')->delete($data['excel_file']);
                        
                        if ($result['success']) {
                            // Refresh data
                            $this->mount();
                            
                            $notification = Notification::make()
                                ->title('Import Berhasil')
                                ->body($result['message'])
                                ->success();
                                
                            if (!empty($result['errors'])) {
                                $notification->body($result['message'] . "\n\nError:\n" . implode("\n", array_slice($result['errors'], 0, 5)));
                            }
                            
                            $notification->send();
                        } else {
                            Notification::make()
                                ->title('Import Gagal')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        // Clean up uploaded file on error
                        if (isset($data['excel_file'])) {
                            Storage::disk('local')->delete($data['excel_file']);
                        }
                        
                        Notification::make()
                            ->title('Import Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->modalHeading('Import Data dari Excel')
                ->modalSubheading('Upload file Excel dengan format yang sesuai untuk mengimpor data alternatif, biodata, dan penilaian.')
                ->modalButton('Import Data'),

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
