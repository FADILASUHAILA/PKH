<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\HasilPerangkingan;
use App\Http\Controllers\PencarianController;
use App\Http\Controllers\HasilController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/hasil-perangkingan/pdf', [HasilPerangkingan::class, 'downloadPdf'])->name('hasil-perangkingan.pdf');

Route::prefix('/')->group(function () {
    Route::get('/', [PencarianController::class, 'index'])->name('pencarian.index');
    Route::get('/cari', [HasilController::class, 'cariByNik'])->name('pencarian.cari');
    Route::delete('/penilaian/{alternatif}', [\App\Filament\Pages\Penilaian::class, 'delete'])
    ->name('filament.admin.pages.delete-penilaian');
});

// Routes untuk Sistem Penetapan Status Kelulusan PKH
Route::prefix('hasil')->name('hasil.')->group(function () {
    Route::get('/', [HasilController::class, 'index'])->name('index');
    Route::get('/cari-nik', [HasilController::class, 'cariByNik'])->name('cari-nik');
    Route::get('/api/cari-nik', [HasilController::class, 'apiCariNik'])->name('api-cari-nik');
    Route::post('/penetapan-status', [HasilController::class, 'penetapanStatus'])->name('penetapan-status');
    Route::get('/desa/{desa}', [HasilController::class, 'detailDesa'])->name('detail-desa');
    Route::get('/export', [HasilController::class, 'export'])->name('export');
    Route::get('/statistik', [HasilController::class, 'statistik'])->name('statistik');
});