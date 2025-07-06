<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\HasilPerangkingan;
use App\Http\Controllers\PencarianController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/hasil-perangkingan/pdf', [HasilPerangkingan::class, 'downloadPdf'])->name('hasil-perangkingan.pdf');

Route::prefix('/')->group(function () {
    Route::get('/', [PencarianController::class, 'index'])->name('pencarian.index');
    Route::get('/cari', [PencarianController::class, 'cariByNik'])->name('pencarian.cari');
    Route::delete('/penilaian/{alternatif}', [\App\Filament\Pages\Penilaian::class, 'delete'])
    ->name('filament.admin.pages.delete-penilaian');
});