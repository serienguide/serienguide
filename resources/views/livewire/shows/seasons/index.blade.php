<div x-data="{ open: {{ $isCurrent ? 'true' : 'false' }} }">
    <div class="flex my-3 items-end">
        @if ($season->poster_path)
            <img @if(is_null($items)) wire:click="load" @endif @click="open = !open;" src="{{ Storage::disk('s3')->url('w118' . $season->poster_path) }}" class="mr-1 cursor-pointer">
        @endif
        <div @if(is_null($items)) wire:click="load" @endif @click="open = !open;" class="cursor-pointer flex-grow">
            <div class="flex">
                <h3>Staffel {{ $season->season_number }}</h3>
                <div wire:loading.delay class="ml-2 pointer-events-none">
                    <i class="fa fa-spinner fa-spin text-gray-400"></i>
                </div>
            </div>
            <div class="text-sm">{{ $season->episode_count }} Folgen</div>
        </div>
        @auth
            <div>
                <button wire:click="watch" @click="open = true;" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                    Gesehen
                </button>
            </div>
        @endauth
    </div>
    <div class="my-3 rounded h-2 w-full bg-blue-900" title="{{ $season->progress['watched_count'] }}/{{ $season->progress['watchable_count'] }} {{ $season->progress['percent'] }}%">
        <div class="bg-blue-500 h-2 text-xs leading-none text-center text-white transition-all duration-500 @if ($season->progress['percent'] > 0) rounded-l @endif @if ($season->progress['percent'] == 100) rounded-r @endif" style="width: {{ $season->progress['percent'] }}%"></div>
    </div>
    <ul x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-3">
        @if ($items)
            @foreach ($items as $item)
                @livewire('media.card', ['model' => $item, 'type' => 'backdrop', 'load_next' => false], key('media-card-' . $item->id))
            @endforeach
        @endif
    </ul>
</div>
