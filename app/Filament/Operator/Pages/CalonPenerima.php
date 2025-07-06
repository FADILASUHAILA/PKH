<?php

namespace App\Filament\Operator\Pages;

use App\Models\Indikasi;
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
use Illuminate\Support\Facades\DB;

class CalonPenerima extends Page implements HasForms, HasTable
{
    use HasPageShield;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.operator.pages.calon-penerima';

    public ?array $data = [];

    // Daftar kriteria yang menggunakan indikasi
    protected array $kriteriaIndikasi = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

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
                        ->mask('9999999999999999')
                        ->maxLength(16)
                        ->unique(BioData::class, 'nik'),

                    TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    Select::make('desa_id')
                        ->label('Desa')
                        ->options(Desa::all()->pluck('nama_desa', 'id'))
                        ->required()
                        ->searchable(),

                    Textarea::make('alamat')
                        ->label('Alamat')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('no_hp')
                        ->label('Nomor HP')
                        ->required()
                        ->mask('9999999999999999')
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
                    ->with(['desa', 'bioData', 'indikasis'])
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
                        return strlen($state) <= 50 ? null : $state;
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
                // Filter tambahan bisa ditambahkan di sini
            ])
            ->actions([
                ActionsActionGroup::make([
                    $this->getIndikasiAction(),
                    $this->getEditAction(),
                    $this->getDeleteAction(),
                ])->label('Aksi Lainnya')
                    ->icon('heroicon-o-ellipsis-vertical'),
            ])
            ->bulkActions([
                // Bulk actions bisa ditambahkan di sini
            ])
            ->emptyStateHeading('Belum ada data calon penerima')
            ->emptyStateDescription('Klik tombol "Tambah Calon Penerima" untuk menambahkan data baru.')
            ->emptyStateIcon('heroicon-o-users');
    }

    protected function getIndikasiAction(): ActionsAction
    {
        return ActionsAction::make('indikasi')
            ->label('Input Indikasi')
            ->icon('heroicon-o-clipboard-document-check')
            ->color('primary')
            ->form($this->getIndikasiFormSchema())
            ->fillForm(fn(Alternatif $record): array => $this->getExistingIndikasi($record))
            ->action(function (Alternatif $record, array $data): void {
                $this->simpanIndikasi($record, $data);
            })
            ->modalHeading('Input Data Indikasi')
            ->modalSubmitActionLabel('Simpan')
            ->modalCancelActionLabel('Batal');
    }

    protected function getEditAction(): EditAction
    {
        return EditAction::make()
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
                    ->options(Desa::all()->pluck('nama_desa', 'id'))
                    ->required()
                    ->searchable(),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->required()
                    ->numeric()
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
            );
    }

    protected function getDeleteAction(): DeleteAction
    {
        return DeleteAction::make()
            ->requiresConfirmation()
            ->modalHeading('Hapus Calon Penerima')
            ->modalDescription('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.')
            ->modalSubmitActionLabel('Ya, Hapus')
            ->modalCancelActionLabel('Batal')
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Data berhasil dihapus')
            );
    }

    protected function getIndikasiFormSchema(): array
    {
        return [
            TextInput::make('penghasilan')
                ->label('Penghasilan per Bulan (Rp)')
                ->required()
                ->numeric()
                ->minValue(0),

            Select::make('pekerjaan')
                ->label('Pekerjaan')
                ->options(['Tidak bekerja' => 'Tidak bekerja', 'Pekerja harian lepas' => 'Pekerja harian lepas', 'Pekerja tetap' => 'Pekerja tetap'])
                ->required(),

            TextInput::make('jumlah_tanggungan')
                ->label('Jumlah Tanggungan')
                ->required()
                ->numeric()
                ->minValue(0),

            TextInput::make('jumlah_anak_sekolah')
                ->label('Jumlah Anak Sekolah')
                ->required()
                ->numeric()
                ->minValue(0),

            Select::make('ibu_hamil')
                ->label('Ibu Hamil')
                ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada'])
                ->required(),

            Select::make('balita')
                ->label('Balita')
                ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada'])
                ->required(),

            Select::make('anggota_disabilitas')
                ->label('Anggota Disabilitas')
                ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada'])
                ->required(),

            Select::make('lansia')
                ->label('Lansia')
                ->options(['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada'])
                ->required(),

            Select::make('luas_lantai')
                ->label('Luas Lantai (m²)')
                ->options(['<8 m² per orang' => '<8 m² per orang', '8-15 m² per orang' => '8-15 m² per orang', '>15 m² per orang' => '>15 m² per orang'])
                ->required(),

            Select::make('jenis_lantai')
                ->label('Jenis Lantai')
                ->options(['Tanah' => 'Tanah', 'Bambu' => 'Bambu', 'semen' => 'semen', 'Keramik' => 'Keramik'])
                ->required(),

            Select::make('jenis_dinding')
                ->label('Jenis Dinding')
                ->options(['Bambu/rumbia/kayu rendah' => 'Bambu/rumbia/kayu rendah', 'Tembok/Semen' => 'Tembok/Semen'])
                ->required(),
        ];
    }

    protected function getPenilaianFormSchema(): array
    {
        $kriteria = Kriteria::with('subKriterias')->get();

        $fields = [];

        foreach ($kriteria as $k) {
            // Skip kriteria yang sudah dihandle oleh indikasi
            if (in_array($k->id, $this->kriteriaIndikasi)) {
                continue;
            }

            $fields[] = Select::make('kriteria_' . $k->id)
                ->label($k->nama_kriteria)
                ->options($k->subKriterias->pluck('nama_sub_kriteria', 'id'))
                ->required()
                ->searchable();
        }

        return $fields;
    }

    protected function getExistingIndikasi(Alternatif $record): array
    {
        $indikasi = $record->indikasis()->first();
        return $indikasi ? $indikasi->toArray() : [];
    }

    protected function getExistingPenilaian(Alternatif $record): array
    {
        $data = [];
        $penilaians = $record->penilaian()->with('subKriteria')->get();

        foreach ($penilaians as $penilaian) {
            // Hanya ambil penilaian untuk kriteria non-indikasi
            if (!in_array($penilaian->kriteria_id, $this->kriteriaIndikasi)) {
                $data['kriteria_' . $penilaian->kriteria_id] = $penilaian->subkriteria_id;
            }
        }

        return $data;
    }

    protected function simpanIndikasi(Alternatif $record, array $data): void
    {
        DB::beginTransaction();
        try {
            // Simpan atau update data indikasi
            $indikasi = $record->indikasis()->updateOrCreate(
                ['alternatif_id' => $record->id],
                $data
            );

            // Konversi ke penilaian dalam transaction yang sama
            $indikasi->konversiKePenilaian();

            DB::commit();

            Notification::make()
                ->title('Berhasil')
                ->body('Data indikasi dan penilaian berhasil disimpan')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
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
            DB::transaction(function () use ($data) {
                // Validasi NIK unik
                if (BioData::where('nik', $data['nik'])->exists()) {
                    throw new \Exception('NIK sudah terdaftar');
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
            });

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
                    ->options(Desa::all()->pluck('nama_desa', 'id'))
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
