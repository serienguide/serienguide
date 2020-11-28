<div class="mb-3">
    <div class="mb-3">
        <label for="search" class="block text-sm font-medium leading-5 text-gray-700">Suche</label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input wire:model.debounce.250ms="filter.search" id="search" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="Suche">
        </div>
    </div>
    <x-media.cards.deck :items="$items"/>
</div>
