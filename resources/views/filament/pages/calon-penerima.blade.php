<x-filament::page>
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
    
    @if (session('status'))
        <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('status') }}
        </div>
    @endif
</x-filament::page>