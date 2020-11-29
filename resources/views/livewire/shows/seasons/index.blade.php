<div x-data="{ open: {{ $season->season_number == 1 ? 'true' : 'false' }} }">
    <div class="flex my-3 items-end">
        @if ($season->poster_path)
            <img @if(is_null($items)) wire:click="load" @endif @click="open = !open;" src="{{ Storage::disk('s3')->url('w118' . $season->poster_path) }}" class="mr-1 cursor-pointer">
        @endif
        <div @if(is_null($items)) wire:click="load" @endif @click="open = !open;" class="cursor-pointer">
            <h3>Staffel {{ $season->season_number }}</h3>
            <div class="text-sm">{{ $season->episode_count }} Folgen</div>
        </div>
    </div>
    <div wire:loading.delay class="text-center w-100 p-5">
        Lade Daten.
    </div>
    <ul x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-3">
        @if ($items)
            @foreach ($items as $item)
                @livewire('media.card', ['model' => $item, 'type' => 'backdrop'], key('media-card-' . $item->id))
            @endforeach
        @endif
    </ul>
</div>
