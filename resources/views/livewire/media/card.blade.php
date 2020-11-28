<li class="col-span-1 flex flex-col justify-between bg-white rounded-lg shadow">
    <header class="flex items-center justify-center p-3">
        @auth
            <div class="flex-grow"></div>
            @if(get_class($model) != \App\Models\Shows\Episodes\Episode::class)
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <div class="px-1">
                        <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                            <i class="fas fa-list"></i>
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
                                @livewire('media.listing', ['model' => $model])
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="relative inline-block text-left" x-data="{ open: false }">
                <div class="px-1">
                    <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                        <i class="fas fa-star {{ is_null($model->rating_by_user) ? '' : 'text-yellow-400' }}"></i> {{ is_null($model->rating_by_user) ? '' : $model->rating_by_user->rating }}
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
                        <div class="flex justify-between p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" x-data="{ rating: {{ is_null($model->rating_by_user) ? 0 : $model->rating_by_user->rating }}, hovered: {{ is_null($model->rating_by_user) ? 0 : $model->rating_by_user->rating }} }" @mouseleave="hovered = rating > 0 ? rating : 0">
                            @if (! is_null($model->rating_by_user))
                                <i wire:click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-red-500" @mouseenter="hovered = 0"></i>
                            @endif
                            @for ($i = 1; $i <= 10; $i++)
                                <i wire:click="rate({{ $i }})" data-rating="{{ $i }}" class="fas fa-star px-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= {{ $i }}}" @mouseenter="hovered = {{ $i }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            @if(get_class($model) != \App\Models\Shows\Show::class)
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <div class="px-1">
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
            @endif
        @endauth

    </header>
    <main class="flex-grow">
        <a href="{{ $model->path }}" title="{{ $model->name }}">
            <img src="{{ $model->card_image_path }}">
        </a>
    </main>
    <footer class="flex items-center p-3">
        <h3 class="flex-grow text-gray-900 leading-5 font-medium overflow-hidden whitespace-nowrap"><a href="{{ $model->path }}" title="{{ $model->name }}">{{ $model->name }}</a></h3>
        @auth
        <div class="ml-1">
            <button wire:click="watch" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md whitespace-no-wrap hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 {{ $button_class }}" title="@if($model->watched->count()) ({{ $model->watched->count() }}) mal gesehen @endif">
                <i class="fas fa-check"></i>
            </button>
        </div>
        @endauth
    </footer>
</li>