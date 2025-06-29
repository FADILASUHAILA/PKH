<x-filament::page>
    {{-- Tabel untuk menampilkan data --}}
    <div class="mb-6">
        {{ $this->table }}
    </div>

    {{-- Form tambahan (opsional, bisa dihapus jika tidak diperlukan) --}}
    @if(false) {{-- Set ke true jika ingin menampilkan form di halaman --}}
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-medium mb-4">Tambah Data Manual</h3>
        <form wire:submit="create">
            {{ $this->form }}
            
            <x-filament::button 
                type="submit" 
                class="mt-4"
                wire:loading.attr="disabled"
                wire:target="create"
            >
                <span wire:loading.remove wire:target="create">Simpan Data</span>
                <span wire:loading wire:target="create">Menyimpan...</span>
            </x-filament::button>
        </form>
    </div>
    @endif

    @if (session('status'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('status') }}
        </div>
    @endif
</x-filament::page>