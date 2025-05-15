<x-filament::page>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Penilaian - Cadek</h1>
        </div>

        <!-- Form Kriteria -->
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-6">
                    <!-- Kriteria 1 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C1 - Perizinan</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih status perizinan</option>
                                <option value="lengkap">Sudah memiliki perizinan yang lengkap</option>
                                <option value="sebagian">Memiliki sebagian perizinan</option>
                                <option value="tidak">Tidak memiliki perizinan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kriteria 3 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C3 - Harga lahan</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih range harga</option>
                                <option value="<=1jt">&lt;= Rp 1.000.000 / m²</option>
                                <option value="1-2jt">Rp 1.000.000 - 2.000.000 / m²</option>
                                <option value=">=2jt">&gt;= Rp 2.000.000 / m²</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kriteria 5 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-red-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C5 - Sarana pendidikan</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih jarak ke sarana pendidikan</option>
                                <option value="<=2km">&lt;= 2 km</option>
                                <option value="2-5km">2 - 5 km</option>
                                <option value=">=5km">&gt;= 5 km</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kriteria 7 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-pink-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C7 - Sasaran pembeli</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih sasaran pembeli</option>
                                <option value="investor">Investor Properti</option>
                                <option value="perorangan">Perorangan</option>
                                <option value="developer">Developer</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">
                    <!-- Kriteria 2 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-green-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C2 - Jarak dengan fasilitas umum</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih jarak ke fasilitas umum</option>
                                <option value="<=5km">&lt;= 5 km</option>
                                <option value="5-10km">5 - 10 km</option>
                                <option value=">=10km">&gt;= 10 km</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kriteria 4 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C4 - Jarak ke pusat kota</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih jarak ke pusat kota</option>
                                <option value="<=2km">&lt;= 2 km</option>
                                <option value="2-5km">2 - 5 km</option>
                                <option value=">=5km">&gt;= 5 km</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kriteria 6 -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-indigo-500">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">C6 - Aksesibilitas jalan</h2>
                        <div class="space-y-4">
                            <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="" disabled selected>Pilih jenis jalan</option>
                                <option value="beraspal">Beraspal</option>
                                <option value="setengah">Setengah beraspal</option>
                                <option value="tanah">Jalan tanah</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end mt-8">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 transition duration-300 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-filament::page>