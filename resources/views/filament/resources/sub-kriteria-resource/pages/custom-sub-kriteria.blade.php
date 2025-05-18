<x-filament::page>
    <div class="space-y-8">
        <!-- Header with Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gradient-to-r from-primary-500 to-primary-600 rounded-xl shadow-md p-6 text-dark">
                <h3 class="text-sm font-medium">Total Kriteria</h3>
                <p class="text-3xl font-bold mt-2">{{ $kriterias->count() }}</p>
                <div class="mt-4 flex items-center text-primary-100">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    <span class="text-xs">+{{ rand(2,10) }}% from last month</span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Subkriteria</h3>
                <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">{{ $kriterias->sum(function($kriteria) { return count($kriteria->subKriterias); }) }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Subkriteria</h3>
                <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">{{ round($kriterias->avg(function($kriteria) { return count($kriteria->subKriterias); }), 1) }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bobot</h3>
                <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-white">{{ $kriterias->sum('bobot') }}%</p>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Kriteria</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manajemen kriteria dan subkriteria sistem</p>
            </div>
            <div class="flex space-x-3">
                <x-filament::button 
                    icon="heroicon-o-plus"
                    tag="a"
                    href="{{ route('filament.admin.resources.kriterias.create') }}"
                    size="sm">
                    Tambah Kriteria
                </x-filament::button>
                
                <x-filament::button 
                    icon="heroicon-o-document-text"
                    color="gray"
                    size="sm"
                    outlined>
                    Export
                </x-filament::button>
            </div>
        </div>

        <!-- Kriteria Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($kriterias as $kriteria)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all duration-200 hover:shadow-lg">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 h-10 w-10 rounded-lg bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-200 flex items-center justify-center">
                            <span class="font-bold">{{ $loop->iteration }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $kriteria->nama_kriteria }}</h3>
                            <div class="flex items-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                    {{ $kriteria->bobot }}% Bobot
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                    {{ count($kriteria->subKriterias) }} Sub
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <x-filament::button 
                            icon="heroicon-o-pencil"
                            color="gray"
                            tag="a"
                            href="{{ route('filament.admin.resources.kriterias.edit', $kriteria) }}"
                            size="xs"
                            outlined>
                        </x-filament::button>
                    </div>
                </div>

                <!-- Subkriteria List -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($kriteria->subKriterias as $sub)
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-300">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $sub->nama_sub_kriteria }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nilai: {{ $sub->nilai }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <x-filament::button 
                                    icon="heroicon-o-pencil"
                                    color="gray"
                                    tag="a"
                                    href="{{ route('filament.admin.resources.sub-kriterias.edit', $sub) }}"
                                    size="xs"
                                    outlined>
                                </x-filament::button>
                                
                                <form action="" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-filament::button 
                                        icon="heroicon-o-trash"
                                        color="danger"
                                        size="xs"
                                        outlined
                                        type="submit"
                                        onclick="return confirm('Hapus subkriteria ini?')">
                                    </x-filament::button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada subkriteria</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tambahkan subkriteria untuk kriteria ini</p>
                        <div class="mt-4">
                            <x-filament::button 
                                icon="heroicon-o-plus"
                                tag="a"
                                href="{{ route('filament.admin.resources.sub-kriterias.create', ['kriteria_id' => $kriteria->id]) }}"
                                size="xs">
                                Tambah Subkriteria
                            </x-filament::button>
                        </div>
                    </div>
                    @endforelse
                </div>

                <!-- Add Subkriteria Footer -->
                @if(count($kriteria->subKriterias) > 0)
                <div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30 text-right">
                    <x-filament::button 
                        icon="heroicon-o-plus"
                        tag="a"
                        href="{{ route('filament.admin.resources.sub-kriterias.create', ['kriteria_id' => $kriteria->id]) }}"
                        size="xs">
                        Tambah Subkriteria
                    </x-filament::button>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($kriterias->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Belum ada kriteria</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat kriteria baru untuk sistem penilaian Anda.</p>
            <div class="mt-6">
                <x-filament::button 
                    icon="heroicon-o-plus"
                    tag="a"
                    href="{{ route('filament.admin.resources.kriterias.create') }}"
                    size="sm">
                    Tambah Kriteria Pertama
                </x-filament::button>
            </div>
        </div>
        @endif
    </div>
</x-filament::page>