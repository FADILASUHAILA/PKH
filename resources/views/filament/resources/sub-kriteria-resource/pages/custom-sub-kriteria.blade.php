<x-filament::page>
    <div class="space-y-6">
        @foreach($kriterias as $kriteria)
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">
                    {{ $kriteria->nama_kriteria }}
                    <span class="text-sm text-gray-500 ml-2">
                        ({{ count($kriteria->subKriterias) }} subkriteria)
                    </span>
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Sub Kriteria</th>
                                <th class="px-4 py-2 text-left">Nilai</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kriteria->subKriterias as $sub)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $sub->nama_sub_kriteria }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $sub->nilai }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <a href="{{ route('filament.admin.resources.sub-kriterias.edit', $sub) }}"
                                           class="text-blue-600 hover:text-blue-900">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-center text-gray-500">
                                        Belum ada subkriteria
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('filament.admin.resources.sub-kriterias.create', ['kriteria_id' => $kriteria->id]) }}"
                       class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 transition">
                        + Tambah Subkriteria
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</x-filament::page>