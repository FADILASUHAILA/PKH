<x-filament-panels::page>
    @if(empty($results))
        <div class="p-4 text-center text-gray-500 dark:text-gray-400">
            Tidak ada data hasil perhitungan. Silahkan lakukan perhitungan PROMETHEE terlebih dahulu.
        </div>
    @else
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                Hasil Perhitungan PROMETHEE
            </h1>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px" id="prometheeTabs" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="ranking-tab" data-tabs-target="#ranking" type="button" role="tab">
                        Ranking
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="matrix-tab" data-tabs-target="#matrix" type="button" role="tab">
                        Matriks Keputusan
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="preference-tab" data-tabs-target="#preference" type="button" role="tab">
                        Matriks Preferensi
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                        id="flows-tab" data-tabs-target="#flows" type="button" role="tab">
                        Leaving & Entering Flow
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Contents -->
        <div id="prometheeTabContent">
            <!-- 1. Ranking Tab -->
            <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" id="ranking" role="tabpanel">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ranking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alternatif</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Net Flow (Φ)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($ranking as $altId => $rank)
                            @php
                                $alternatif = $alternatifs->firstWhere('id', $altId);
                            @endphp
                            <tr class="@if($loop->odd) bg-gray-50 dark:bg-gray-700 @endif">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $rank }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $alternatif->nama ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold 
                                    @if(($netFlow[$altId] ?? 0) > 0) text-green-600 dark:text-green-400 
                                    @elseif(($netFlow[$altId] ?? 0) < 0) text-red-600 dark:text-red-400 
                                    @else text-gray-600 dark:text-gray-400 @endif">
                                    {{ number_format($netFlow[$altId] ?? 0, 4) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Data ranking tidak tersedia
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. Decision Matrix Tab -->
            <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" id="matrix" role="tabpanel">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alternatif</th>
                                @foreach($kriterias as $kriteria)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $kriteria->kode }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alternatifs as $alternatif)
                            <tr class="@if($loop->odd) bg-gray-50 dark:bg-gray-700 @endif">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $alternatif->nama }}
                                </td>
                                @foreach($kriterias as $kriteria)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $decisionMatrix[$alternatif->id][$kriteria->id] ?? 'N/A' }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. Preference Matrix Tab -->
            <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" id="preference" role="tabpanel">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alternatif</th>
                                @foreach($alternatifs as $alternatif)
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    {{ $alternatif->nama }}
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alternatifs as $altA)
                            <tr class="@if($loop->odd) bg-gray-50 dark:bg-gray-700 @endif">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $altA->nama }}
                                </td>
                                @foreach($alternatifs as $altB)
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($preferenceMatrix[$altA->id][$altB->id] ?? 0, 4) }}
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 4. Flows Tab -->
            <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" id="flows" role="tabpanel">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alternatif</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Leaving Flow (Φ+)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Entering Flow (Φ-)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Net Flow (Φ)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($alternatifs as $alternatif)
                            <tr class="@if($loop->odd) bg-gray-50 dark:bg-gray-700 @endif">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $alternatif->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($leavingFlow[$alternatif->id] ?? 0, 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($enteringFlow[$alternatif->id] ?? 0, 4) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold 
                                    @if(($netFlow[$alternatif->id] ?? 0) > 0) text-green-600 dark:text-green-400 
                                    @elseif(($netFlow[$alternatif->id] ?? 0) < 0) text-red-600 dark:text-red-400 
                                    @else text-gray-600 dark:text-gray-400 @endif">
                                    {{ number_format($netFlow[$alternatif->id] ?? 0, 4) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tabs
            const tabs = document.querySelectorAll('[data-tabs-target]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Hide all tab panels
                    document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
                        panel.classList.add('hidden');
                    });
                    
                    // Show selected panel
                    const target = document.querySelector(this.getAttribute('data-tabs-target'));
                    target.classList.remove('hidden');
                    
                    // Update active tab styling
                    document.querySelectorAll('[role="presentation"] button').forEach(btn => {
                        btn.classList.remove('border-blue-600', 'text-blue-600', 'dark:border-blue-500', 'dark:text-blue-500');
                        btn.classList.add('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
                    });
                    
                    this.classList.remove('border-transparent', 'hover:text-gray-600', 'hover:border-gray-300', 'dark:hover:text-gray-300');
                    this.classList.add('border-blue-600', 'text-blue-600', 'dark:border-blue-500', 'dark:text-blue-500');
                });
            });
            
            // Activate first tab by default
            if (tabs.length > 0) {
                tabs[0].click();
            }
        });
    </script>
    @endpush
</x-filament-panels::page>