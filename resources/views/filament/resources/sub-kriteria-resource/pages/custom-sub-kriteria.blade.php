<x-filament::page>
    <div class="space-y-8">
        <!-- Add Kriteria Button -->
        <div class="flex justify-start mb-8">
            <x-filament::button
                icon="heroicon-o-plus"
                tag="a"
                href="{{ route('filament.admin.resources.kriterias.create') }}"
                size="sm">
                Tambah Kriteria Baru
            </x-filament::button>
        </div>

        <!-- Kriteria Cards -->
        @foreach($kriterias as $kriteria)
        <div class="bg-white mb-8 dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700 transition-all hover:shadow-xl">
            <!-- Card Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-10 w-10 mr-2 rounded-lg bg-primary-100 dark:bg-blue-900 text-primary-600 dark:text-blue-200 flex items-center justify-center">
                        <span class="font-bold">{{ $loop->iteration }}</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ $kriteria->nama_kriteria }}
                        </h2>
                        <div class="flex space-x-2 mt-1">
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100">
                                Bobot: {{ $kriteria->bobot }}%
                            </span>
                            <span class="text-xs px-2 py-1 rounded-full bg-primary-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                {{ count($kriteria->subKriterias) }} Subkriteria
                            </span>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-3 text-right">
                    <x-filament::button
                        icon="heroicon-o-plus"
                        tag="a"
                        href="{{ route('filament.admin.resources.sub-kriterias.create', ['kriteria_id' => $kriteria->id]) }}"
                        size="xs">
                        Tambah Sub-Kriteria
                    </x-filament::button>
                </div>
            </div>

            <!-- Subkriteria Table -->
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-8">
                                No
                            </th>

                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider" style="width: 450px">
                                Sub Kriteria
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Nilai
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($kriteria->subKriterias as $sub)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $sub->nama_sub_kriteria }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                    {{ $sub->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
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
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak ada subkriteria</h3>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tambahkan subkriteria untuk kriteria ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

        <!-- Empty State -->
        @if($kriterias->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
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