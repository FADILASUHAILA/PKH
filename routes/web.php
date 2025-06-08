<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\HasilPerangkingan;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hasil-perangkingan/pdf', [HasilPerangkingan::class, 'downloadPdf'])->name('hasil-perangkingan.pdf');

