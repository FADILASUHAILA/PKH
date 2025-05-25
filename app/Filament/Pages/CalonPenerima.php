<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\Desa;
use App\Models\Alternatif;
use App\Models\BioData;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;

class CalonPenerima extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.calon-penerima';

    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nik')
                    ->label('NIK')
                    ->required()
                    ->numeric()
                    ->maxLength(16),

                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                Select::make('desa_id')
                    ->label('Desa')
                    ->options(function () {
                        return Desa::all()->pluck('nama_desa', 'id');
                    })
                    ->required()
                    ->reactive(),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->required()
                    ->tel()
                    ->maxLength(15),
            ])
            ->statePath('data')
            ->model(Alternatif::class);
    }

    public function create(): void
    {
        // Validasi dulu
        $this->validate([
            'data.nik' => 'required|numeric|digits:16',
            'data.nama' => 'required|string|max:255',
            'data.desa_id' => 'required|exists:desas,id',
            'data.alamat' => 'required|string',
            'data.no_hp' => 'required|string|max:15',
        ]);

        // Debug data sebelum simpan (optional)
        logger()->info('Form data:', $this->data);

        // Pastikan desa_id ada
        if (!isset($this->data['desa_id'])) {
            $this->notify('danger', 'Desa harus dipilih');
            return;
        }

        // Simpan data alternatif
        $alternatif = Alternatif::create([
            'kode' => 'ALT-' . Str::random(8),
            'nama' => $this->data['nama'],
            'desa_id' => $this->data['desa_id'],
        ]);

        // Simpan biodata terkait
        BioData::create([
            'nik' => $this->data['nik'],
            'alamat' => $this->data['alamat'],
            'no_hp' => $this->data['no_hp'],
            'alternatif_id' => $alternatif->id,
        ]);

        $this->form->fill();
        // Notifikasi sukses
        Notification::make()
            ->title('Berhasil')
            ->body('Data calon penerima berhasil disimpan')
            ->success()
            ->send();
    }
}
