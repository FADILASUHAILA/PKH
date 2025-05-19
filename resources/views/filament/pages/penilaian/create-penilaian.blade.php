<x-filament-panels::page>
    <div class="max-w-6xl">
        <!-- Header -->
        <div class="text-center py-2 mb-8 bg-white rounded-lg shadow-md">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Penilaian - {{ $alternatif_id->nama }}</h1>
        </div>

        <!-- Form Kriteria -->
        <form wire:submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    @foreach($leftColumnKriterias as $kriteria)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 @switch($loop->iteration)
                    @case(1) border-blue-500 @break
                    @case(2) border-yellow-500 @break
                    @case(3) border-red-500 @break
                    @case(4) border-pink-500 @break
                    @default border-gray-500
                @endswitch">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                            {{ $kriteria->kode }} - {{ $kriteria->nama_kriteria }}
                        </h2>
                        <div class="space-y-4">
                            <select
                                wire:model="kriteria.{{ $kriteria->id }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                                <option value="" disabled selected>Pilih {{ $kriteria->nama_kriteria }}</option>
                                @foreach($kriteria->subkriterias as $subkriteria)
                                <option value="{{ $subkriteria->id }}">
                                    {{ $subkriteria->nama_sub_kriteria }}

                                </option>
                                @endforeach
                            </select>
                            @error('kriteria.'.$kriteria->id) <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    @foreach($rightColumnKriterias as $kriteria)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 @switch($loop->iteration)
                    @case(1) border-green-500 @break
                    @case(2) border-purple-500 @break
                    @case(3) border-indigo-500 @break
                    @default border-gray-500
                @endswitch">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
                            {{ $kriteria->kode }} - {{ $kriteria->nama_kriteria }}
                        </h2>
                        <div class="space-y-4">
                            <select
                                wire:model="kriteria.{{ $kriteria->id }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                required>
                                <option value="" disabled selected>Pilih {{ $kriteria->nama_kriteria }}</option>
                                @foreach($kriteria->subkriterias as $subkriteria)
                                <option value="{{ $subkriteria->id }}">
                                    {{ $subkriteria->nama_sub_kriteria }}

                                </option>
                                @endforeach
                            </select>
                            @error('kriteria.'.$kriteria->id) <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex gap-2 justify-end mt-8">
                <a href="{{ route('filament.admin.pages.penilaian') }}"
                    class="filament-button inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset filament-button-size-sm inline-flex items-center justify-center py-1 gap-1 text-sm font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2rem] px-3 text-white bg-success-600 hover:bg-success-500 focus:bg-success-700 focus:ring-success-500 border-transparent">
                    Kembali
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-filament-panels::page>