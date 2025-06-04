<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use App\Models\Desa;
use App\Models\Alternatif;
use App\Models\BioData;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\CreateAction;

class CalonPenerima extends Page implements HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.calon-penerima';

    public ?array $data = [];

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Tambah Calon Penerima')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('nik')
                        ->label('NIK')
                        ->required()
                        ->numeric()
                        ->maxLength(16)
                        ->unique(BioData::class, 'nik'),

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
                        ->searchable(),

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
                ->action(function (array $data): void {
                    $this->createRecord($data);
                })
                ->modalHeading('Tambah Calon Penerima')
                ->modalSubmitActionLabel('Simpan')
                ->modalCancelActionLabel('Batal'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Alternatif::query()
                    ->with(['desa', 'bioData'])
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bioData.nik')
                    ->label('NIK')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('desa.nama_desa')
                    ->label('Desa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bioData.alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                TextColumn::make('bioData.no_hp')
                    ->label('No. HP')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                // Anda bisa menambahkan filter berdasarkan desa jika diperlukan
            ])
            ->actions([
                EditAction::make()
                    ->form([
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
                            ->searchable(),

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
                    ->fillForm(function (Alternatif $record): array {
                        return [
                            'nik' => $record->bioData->nik ?? '',
                            'nama' => $record->nama,
                            'desa_id' => $record->desa_id,
                            'alamat' => $record->bioData->alamat ?? '',
                            'no_hp' => $record->bioData->no_hp ?? '',
                        ];
                    })
                    ->using(function (Alternatif $record, array $data): Alternatif {
                        $record->update([
                            'nama' => $data['nama'],
                            'desa_id' => $data['desa_id'],
                        ]);

                        $record->bioData()->updateOrCreate(
                            ['alternatif_id' => $record->id],
                            [
                                'nik' => $data['nik'],
                                'alamat' => $data['alamat'],
                                'no_hp' => $data['no_hp'],
                            ]
                        );

                        return $record;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data berhasil diperbarui')
                    ),

                DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Calon Penerima')
                    ->modalDescription('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data berhasil dihapus')
                    ),
            ])
            ->bulkActions([
                // Anda bisa menambahkan bulk actions jika diperlukan
            ])
            ->emptyStateHeading('Belum ada data calon penerima')
            ->emptyStateDescription('Klik tombol "Tambah Calon Penerima" untuk menambahkan data baru.')
            ->emptyStateIcon('heroicon-o-users');
    }

    protected function createRecord(array $data): void
    {
        try {
            // Validasi NIK unik
            if (BioData::where('nik', $data['nik'])->exists()) {
                Notification::make()
                    ->title('Error')
                    ->body('NIK sudah terdaftar')
                    ->danger()
                    ->send();
                return;
            }

            // Simpan data alternatif
            $alternatif = Alternatif::create([
                'kode' => 'ALT-' . Str::random(8),
                'nama' => $data['nama'],
                'desa_id' => $data['desa_id'],
            ]);

            // Simpan biodata terkait
            BioData::create([
                'nik' => $data['nik'],
                'alamat' => $data['alamat'],
                'no_hp' => $data['no_hp'],
                'alternatif_id' => $alternatif->id,
            ]);

            // Notifikasi sukses
            Notification::make()
                ->title('Berhasil')
                ->body('Data calon penerima berhasil disimpan')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    // Method lama untuk form di halaman (jika masih diperlukan)
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
            'data.nik' => 'required|numeric|digits:16|unique:bio_data,nik',
            'data.nama' => 'required|string|max:255',
            'data.desa_id' => 'required|exists:desas,id',
            'data.alamat' => 'required|string',
            'data.no_hp' => 'required|string|max:15',
        ]);

        $this->createRecord($this->data);
        $this->form->fill();
    }
}