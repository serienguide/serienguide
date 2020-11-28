<div class="mb-3">
    <div class="mb-3">
        <label for="search" class="block text-sm font-medium leading-5 text-gray-700">Suche</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input wire:model.debounce.250ms="filter.search" id="search" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="Suche">
        </div>
    </div>
    <div wire:loading.delay class="text-center w-100 p-5">
        Lade Daten.
    </div>
    <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
        @forelse ($items as $item)
            @livewire('media.card', ['model' => $item], key('media-card-' . $item->id))
        @empty
            Keine Serien vorhanden
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
