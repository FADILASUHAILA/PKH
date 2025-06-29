@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-rose-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full mx-auto">
        <!-- Logo or Branding -->
        <div class="text-center mb-8">
            <svg class="mx-auto h-12 w-12 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <h2 class="mt-2 text-2xl font-extralight text-gray-800">Sistem Informasi</h2>
            <h1 class="text-3xl font-bold text-rose-600">Bantuan PKH</h1>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-rose-100">
            <!-- Header -->
            <div class="bg-white px-6 py-8 border-b border-rose-100">
                <h2 class="text-2xl font-light text-center text-gray-800">Cari Calon Penerima</h2>
                <p class="mt-1 text-sm text-center text-rose-500">Masukkan Nomor Induk Kependudukan (NIK) penerima</p>
            </div>
            
            <!-- Form -->
            <div class="px-6 py-6">
                @if(session('error'))
                <div class="mb-6 p-3 bg-rose-50 rounded-lg flex items-start">
                    <svg class="w-5 h-5 text-rose-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('pencarian.cari') }}" method="GET" class="space-y-5">
                    <div>
                        <label for="nik" class="sr-only">Nomor Induk Kependudukan (NIK)</label>
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-rose-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="nik" name="nik" required
                                   class="focus:ring-rose-500 focus:border-rose-500 block w-full pl-10 pr-12 py-3 border-rose-300 rounded-md placeholder-rose-300"
                                   placeholder="1108xxxxxxxxxxxx" pattern="\d{16}" title="Masukkan 16 digit NIK">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-rose-400 text-sm">16 digit</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 transition-colors duration-200 ease-in-out">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                            Cari Data
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="bg-rose-50 px-6 py-4 text-center border-t border-rose-100">
                <p class="text-xs text-rose-600">© {{ date('Y') }} Sistem Informasi Bantuan PKH</p>
            </div>
        </div>
        
        <!-- Additional Info -->
        <div class="mt-6 text-center">
            <p class="text-xs text-rose-500">Pastikan NIK yang dimasukkan sesuai dengan Kartu Keluarga</p>
        </div>
    </div>
</div>
@endsection