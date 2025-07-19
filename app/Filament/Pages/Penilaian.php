<?php

namespace App\Filament\Pages;

use App\Models\Alternatif;
use App\Models\BioData;
use App\Models\Desa;
use App\Models\Indikasi;
use App\Models\Penilaian as ModelsPenilaian;
use App\Models\PenilaianHeader;
use App\Services\PrometheeService;
use App\Services\ExcelImportService;
use App\Services\PenilaianService;
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
use Illuminate\Http\Request;
use Livewire\Component;


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
    public $desas;
    public $search = '';
    public $selectedDesa = '';
    public $perPage = 10;
    public $currentPage = 1;

    public function mount()
    {
        $this->search = request()->get('search', '');
        $this->selectedDesa = request()->get('desa', '');
        $this->currentPage = request()->get('page', 1);
        
        $this->desas = Desa::orderBy('nama_desa')->get();
        $this->loadAlternatifs();
    }

    public function loadAlternatifs()
    {
        $query = Alternatif::with(['biodata', 'desa', 'penilaian']);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhereHas('biodata', function ($bioQuery) {
                      $bioQuery->where('nik', 'like', '%' . $this->search . '%')
                               ->orWhere('no_hp', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply village filter
        if (!empty($this->selectedDesa)) {
            $query->where('desa_id', $this->selectedDesa);
        }

        $this->alternatifs = $query->orderBy('nama')->get();
    }

    public function updatedSearch()
    {
        $this->currentPage = 1;
        $this->loadAlternatifs();
    }

    public function updatedSelectedDesa()
    {
        $this->currentPage = 1;
        $this->loadAlternatifs();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->selectedDesa = '';
        $this->currentPage = 1;
        $this->loadAlternatifs();
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->with('penilaian');
    }

    protected function getHeaderActions(): array
    {
        return [
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

            Action::make('updateNilaiOtomatis')
                ->label('Update Nilai Otomatis')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function (PenilaianService $penilaianService) {
                    try {
                        $result = $penilaianService->updateNilaiPenilaian();
                        
                        if ($result['success']) {
                            // Refresh data
                            $this->mount();
                            
                            Notification::make()
                                ->title('Update Berhasil')
                                ->body($result['message'])
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Update Gagal')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Update Gagal')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Update Nilai Penilaian Otomatis')
                ->modalSubheading('Sistem akan mengupdate nilai penilaian berdasarkan subkriteria yang dipilih. Proses ini hanya akan mengupdate data yang belum memiliki nilai.')
                ->modalButton('Ya, Update Sekarang')
                ->visible(function () {
                    // Tampilkan tombol jika ada penilaian yang belum memiliki nilai
                    return \App\Models\Penilaian::whereNull('nilai')->exists();
                }),

            Action::make('hitungPromethee')
                ->label('Perhitungan PROMETHEE')
                ->icon('heroicon-o-calculator')
                ->color('primary')
                ->visible(function () {
                    // Cek apakah ada alternatif yang sudah dinilai lengkap
                    $alternatifsDenganPenilaianLengkap = Alternatif::whereHas('penilaian', function ($query) {
                        $query->whereNotNull('nilai');
                    })->get();
                    
                    $jumlahKriteria = \App\Models\Kriteria::count();
                    
                    foreach ($alternatifsDenganPenilaianLengkap as $alternatif) {
                        $jumlahPenilaian = $alternatif->penilaian()->whereNotNull('nilai')->count();
                        if ($jumlahPenilaian === $jumlahKriteria) {
                            return true; // Ada minimal 1 alternatif dengan penilaian lengkap
                        }
                    }
                    
                    return false;
                })
                ->action(function (PrometheeService $prometheeService) {
                    try {
                        // Validasi data sebelum perhitungan
                        $alternatifs = Alternatif::has('penilaian')->get();
                        $kriterias = \App\Models\Kriteria::all();
                        
                        if ($alternatifs->count() < 2) {
                            throw new \Exception("Minimal 2 alternatif harus memiliki penilaian untuk menjalankan PROMETHEE");
                        }
                        
                        if ($kriterias->count() < 1) {
                            throw new \Exception("Minimal 1 kriteria harus tersedia untuk menjalankan PROMETHEE");
                        }
                        
                        // Cek apakah ada alternatif dengan penilaian lengkap
                        $jumlahKriteria = $kriterias->count();
                        $alternatifLengkap = 0;
                        
                        foreach ($alternatifs as $alternatif) {
                            $jumlahPenilaian = $alternatif->penilaian()->whereNotNull('nilai')->count();
                            if ($jumlahPenilaian === $jumlahKriteria) {
                                $alternatifLengkap++;
                            }
                        }
                        
                        if ($alternatifLengkap < 2) {
                            throw new \Exception("Minimal 2 alternatif harus memiliki penilaian lengkap untuk semua kriteria");
                        }
                        
                        $results = $prometheeService->calculate();

                        Notification::make()
                            ->title('Perhitungan PROMETHEE Berhasil')
                            ->body("Berhasil menghitung {$alternatifLengkap} alternatif dengan {$jumlahKriteria} kriteria")
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
                ->modalHeading('Perhitungan PROMETHEE')
                ->modalSubheading('Sistem akan menjalankan perhitungan PROMETHEE untuk semua alternatif yang telah dinilai lengkap. Proses ini akan menghasilkan ranking berdasarkan metode PROMETHEE.')
                ->modalButton('Ya, Hitung Sekarang')
                ->disabled(function () {
                    // Tombol disabled jika tidak ada data yang cukup
                    $alternatifs = Alternatif::has('penilaian')->get();
                    $kriterias = \App\Models\Kriteria::all();
                    
                    if ($alternatifs->count() < 2 || $kriterias->count() < 1) {
                        return true;
                    }
                    
                    $jumlahKriteria = $kriterias->count();
                    $alternatifLengkap = 0;
                    
                    foreach ($alternatifs as $alternatif) {
                        $jumlahPenilaian = $alternatif->penilaian()->whereNotNull('nilai')->count();
                        if ($jumlahPenilaian === $jumlahKriteria) {
                            $alternatifLengkap++;
                        }
                    }
                    
                    return $alternatifLengkap < 2;
                }),
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
