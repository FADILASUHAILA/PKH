<?php

namespace App\Filament\Operator\Pages;

use App\Models\Kriteria;
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
use App\Models\Penilaian;
use App\Models\SubKriteria;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Actions\ActionGroup as ActionsActionGroup;

class CalonPenerima extends Page implements HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.operator.pages.calon-penerima';

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
                ActionsActionGroup::make([
                    ActionsAction::make('penilaian')
                        ->label('Indikasi')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->color('success')
                        ->form($this->getPenilaianFormSchema())
                        ->fillForm(fn(Alternatif $record): array => $this->getExistingPenilaian($record))
                        ->action(function (Alternatif $record, array $data): void {
                            $this->simpanPenilaian($record, $data);
                        })
                        ->modalHeading('Input Penilaian')
                        ->modalSubmitActionLabel('Simpan')
                        ->modalCancelActionLabel('Batal'),
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
                ])->label('Aksi Lainnya')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                // Anda bisa menambahkan bulk actions jika diperlukan
            ])
            ->emptyStateHeading('Belum ada data calon penerima')
            ->emptyStateDescription('Klik tombol "Tambah Calon Penerima" untuk menambahkan data baru.')
            ->emptyStateIcon('heroicon-o-users');
    }

    // Tambahkan method untuk form schema penilaian
    protected function getPenilaianFormSchema(): array
    {
        $kriteria = Kriteria::with('subKriterias')->get();

        $fields = [
            Select::make('alternatif_id')
                ->label('Masukkan Indikasi')
                ->options(Alternatif::all()->pluck('nama', 'id'))
                ->required()
                ->searchable()
                ->reactive(),
        ];

        foreach ($kriteria as $k) {
            $fields[] = Select::make('kriteria_' . $k->id)
                ->label($k->nama_kriteria)
                ->options($k->subKriterias->pluck('nama_sub_kriteria', 'id'))
                ->required()
                ->searchable();
        }

        return $fields;
    }

    // Tambahkan method untuk mengambil data penilaian yang sudah ada
    protected function getExistingPenilaian(Alternatif $record): array
    {
        $data = ['alternatif_id' => $record->id];

        foreach ($record->penilaian as $penilaian) {
            $data['kriteria_' . $penilaian->kriteria_id] = $penilaian->subkriteria_id;
        }

        return $data;
    }

    // Modifikasi method simpanPenilaian untuk menerima parameter Alternatif
    protected function simpanPenilaian(Alternatif $record, array $data): void
    {
        try {
            // Hapus penilaian lama jika ada
            $record->penilaian()->delete();

            // Simpan penilaian baru
            foreach ($data as $key => $value) {
                if (str_starts_with($key, 'kriteria_')) {
                    $kriteriaId = str_replace('kriteria_', '', $key);
                    $subkriteria = SubKriteria::findOrFail($value);

                    Penilaian::create([
                        'alternatif_id' => $record->id,
                        'kriteria_id' => $kriteriaId,
                        'subkriteria_id' => $value,
                        'nilai' => $subkriteria->bobot,
                    ]);
                }
            }

            Notification::make()
                ->title('Berhasil')
                ->body('Data penilaian berhasil disimpan')
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
