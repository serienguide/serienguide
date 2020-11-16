<li class="col-span-1 flex flex-col justify-between bg-white rounded-lg shadow">
    <header class="flex items-center p-3">
        <div class="flex-grow"></div>

        <div class="relative inline-block text-left" x-data="{ open: false }">
            <div>
                <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                    </svg>
                </button>
            </div>

            <div x-show="open"
                x-cloak
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg z-10">
                <div class="rounded-md bg-white shadow-xs">
                    <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                        @livewire('watched.ul', ['model' => $model])
                    </div>
                </div>
            </div>
        </div>

    </header>
    <main>
        <img src="{{ Storage::disk('s3')->url('w680' . $model->poster_path) }}">
    </main>
    <footer class="flex items-center p-3">
        <h3 class="flex-grow text-gray-900 leading-5 font-medium"><a href="{{ $model->path }}">{{ $model->title }}</a></h3>
        @auth
        <div>
            <button wire:click="watch" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white whitespace-no-wrap hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                Gesehen @if($model->watched->count()) ({{ $model->watched->count() }}) @endif
            </button>
        </div>
        @endauth
    </footer>
</li>