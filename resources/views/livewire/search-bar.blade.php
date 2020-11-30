<div class="flex-grow relative ml-3">
    <div class="relative w-full">
        <label for="search" class="sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div wire:loading class="absolute inset-y-2 right-3 flex items-center pointer-events-none">
                <i class="fa fa-spinner fa-spin text-gray-400"></i>
            </div>
            <input wire:model.delay="query"
                wire:keydown.escape="reset"
                wire:keydown.tab="reset"
                id="search"
                name="search"
                class="block w-full pl-10 pr-10 py-2 border border-transparent rounded-md leading-5 bg-gray-700 text-gray-300 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-white focus:ring-white focus:text-gray-900 sm:text-sm"
                placeholder="Suche"
                type="search"
            />
        </div>
    </div>

    @if(!empty($results))
        <div class="fixed top-0 right-0 bottom-0 left-0" wire:click="reset"></div>

        <ul class="absolute z-10 w-full max-h-72 overflow-scroll divide-y divide-gray-200 list-group bg-white rounded-t-none shadow-lg">
            @forelse($results as $type => $models)
                <li class="px-2 py-4">
                    @switch($type)
                        @case('movies')
                            Filme
                            @break
                        @case('shows')
                            Serien
                            @break
                    @endswitch
                </li>
                @foreach ($models as $model)
                    <li class="px-2 py-4">
                        <a class="flex items-center" href="{{ $model->path }}">
                            @if($model->poster_path)
                                <img class="mr-3" src="{{ $model->poster_url_xs }}" alt="">
                            @endif
                            <p class="text-sm font-medium text-gray-900">{{ $model->name }}</p>
                        </a>
                    </li>
                @endforeach
            @empty
                <li class="px-2 py-4">
                    Keine Ergbnisse vorhanden.
                </li>
            @endforelse
        </ul>
    @endif
</div>
