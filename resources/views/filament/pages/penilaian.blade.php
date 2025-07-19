<x-filament::page>

    @if(session('error'))
    <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <span class="font-medium">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <!-- Card Header with Search and Filter -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Data Penilaian Calon Penerima PKH</h3>
                    <p class="text-sm text-gray-600 mt-1">Kelola dan nilai data calon penerima bantuan PKH</p>
                </div>
                
                <!-- Search and Filter Controls -->
                <div class="flex flex-col sm:flex-row gap-3 lg:w-auto w-full">
                    <!-- Search Input -->
                    <div class="relative flex-1 lg:w-80">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Cari nama, NIK, atau no HP..." 
                            class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition duration-150 ease-in-out"
                        >
                        @if($search)
                            <button 
                                wire:click="$set('search', '')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            >
                                <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Village Filter -->
                    <div class="relative">
                        <select 
                            wire:model.live="selectedDesa"
                            class="block w-full pl-3 pr-10 py-2.5 text-sm border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 rounded-lg bg-white transition duration-150 ease-in-out"
                        >
                            <option value="">Semua Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    @if($search || $selectedDesa)
                        <button 
                            wire:click="resetFilters"
                            class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                        >
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </button>
                    @endif
                </div>
            </div>

            <!-- Filter Summary -->
            @if($search || $selectedDesa)
                <div class="mt-4 flex flex-wrap gap-2">
                    @if($search)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Pencarian: "{{ $search }}"
                        </span>
                    @endif
                    @if($selectedDesa)
                        @php $selectedDesaName = $desas->where('id', $selectedDesa)->first()->nama_desa ?? 'Unknown' @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Desa: {{ $selectedDesaName }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alternatif</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Desa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No HP</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Penilaian</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($alternatifs as $index => $alternatif)
                    @php
                    $jumlahKriteria = \App\Models\Kriteria::count();
                    $jumlahPenilaian = $alternatif->penilaian()->count();

                    // Cek apakah semua penilaian memiliki nilai
                    $penilaianTanpaNilai = $alternatif->penilaian()->whereNull('nilai')->exists();
                    $sudahDinilaiOperator = $jumlahPenilaian > 0 && $penilaianTanpaNilai;
                    $sudahDinilaiLengkap = $jumlahPenilaian === $jumlahKriteria && !$penilaianTanpaNilai;
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <!-- Nomor -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                {{ $index + 1 }}
                            </div>
                        </td>
                        <!-- Nama Alternatif -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">
                                            {{ strtoupper(substr($alternatif->nama, 0, 2)) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $alternatif->nama }}</div>
                                    <div class="text-sm text-gray-500">Calon Penerima PKH</div>
                                </div>
                            </div>
                        </td>
                        <!-- NIK -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">{{ $alternatif->biodata->nik ?? '-' }}</div>
                        </td>
                        <!-- Desa -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div class="text-sm text-gray-900">{{ $alternatif->desa->nama_desa }}</div>
                            </div>
                        </td>
                        <!-- No HP -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div class="text-sm text-gray-900 font-mono">{{ $alternatif->biodata->no_hp ?? '-' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($sudahDinilaiLengkap)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Sudah Dinilai ({{ $jumlahPenilaian }}/{{ $jumlahKriteria }})
                            </span>
                            @elseif($sudahDinilaiOperator)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                Belum dinilai ({{ $jumlahPenilaian }}/{{ $jumlahKriteria }})
                            </span>
                            @elseif($jumlahPenilaian > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proses ({{ $jumlahPenilaian }}/{{ $jumlahKriteria }})
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Belum diindikasi
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                @if($sudahDinilaiLengkap)
                                <a href="{{ route('filament.admin.pages.create-penilaian', ['alternatif_id' => $alternatif->id, 'penilaian_id' => $alternatif->penilaian->first()?->penilaian_id]) }}"
                                    class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2rem] px-3 text-white bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-primary-500 border-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Nilai
                                </a>
                                @elseif($sudahDinilaiOperator)
                                <a href="{{ route('filament.admin.pages.create-penilaian', ['alternatif_id' => $alternatif->id, 'penilaian_id' => $alternatif->penilaian->first()?->penilaian_id]) }}"
                                    class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2rem] px-3 text-white bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-primary-500 border-transparent">
                                    Validasi Nilai
                                </a>
                                @else
                                <a href="{{ route('filament.admin.pages.create-penilaian', ['alternatif_id' => $alternatif->id, 'penilaian_id' => $alternatif->penilaian->first()?->penilaian_id]) }}"
                                    class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2rem] px-3 text-white bg-blue-600 hover:bg-blue-500 focus:bg-blue-700 focus:ring-blue-500 border-transparent">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Beri Nilai
                                </a>
                                @endif

                                <!-- Tombol Delete -->
                                <form action="{{ route('filament.admin.pages.delete-penilaian', $alternatif->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2rem] px-3 text-white bg-red-600 hover:bg-red-500 focus:bg-red-700 focus:ring-red-500 border-transparent">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
                                <p class="text-sm text-gray-500 mb-4">
                                    @if($search || $selectedDesa)
                                        Tidak ada data yang sesuai dengan filter yang diterapkan.
                                    @else
                                        Belum ada data calon penerima PKH yang tersedia.
                                    @endif
                                </p>
                                @if($search || $selectedDesa)
                                    <button 
                                        wire:click="resetFilters"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset Filter
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card Footer with Pagination and Statistics -->
        @if(count($alternatifs) > 0)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <!-- Statistics Summary -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-100 rounded-full mr-2"></div>
                        <span>Sudah Dinilai: {{ $alternatifs->filter(function($alt) { 
                            $jumlahKriteria = \App\Models\Kriteria::count();
                            $jumlahPenilaian = $alt->penilaian()->count();
                            $penilaianTanpaNilai = $alt->penilaian()->whereNull('nilai')->exists();
                            return $jumlahPenilaian === $jumlahKriteria && !$penilaianTanpaNilai;
                        })->count() }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-100 rounded-full mr-2"></div>
                        <span>Dalam Proses: {{ $alternatifs->filter(function($alt) { 
                            $jumlahPenilaian = $alt->penilaian()->count();
                            $penilaianTanpaNilai = $alt->penilaian()->whereNull('nilai')->exists();
                            return $jumlahPenilaian > 0 && $penilaianTanpaNilai;
                        })->count() }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-100 rounded-full mr-2"></div>
                        <span>Belum Dinilai: {{ $alternatifs->filter(function($alt) { 
                            return $alt->penilaian()->count() === 0;
                        })->count() }}</span>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    Total: <span class="font-medium text-gray-900">{{ count($alternatifs) }}</span> data
                </div>
            </div>
            
            <!-- Pagination Info -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Menampilkan <span class="font-medium">{{ count($alternatifs) > 0 ? 1 : 0 }}</span> sampai <span class="font-medium">{{ count($alternatifs) }}</span> dari <span class="font-medium">{{ count($alternatifs) }}</span> hasil
                </div>
                <!-- Future pagination controls can be added here -->
            </div>
        </div>
        @endif
    </div>
</x-filament::page>