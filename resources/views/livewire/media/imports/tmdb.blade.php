<div class="mb-3">
    <label for="search" class="block text-sm font-medium leading-5 text-gray-700">Suche <i wire:loading.delay class="fa fa-spinner fa-spin text-grey"></i></label>
    <div class="mt-1 relative rounded-md shadow-sm">
        <input wire:model.debounce.250ms="filter.search" id="search" class="form-input block w-full sm:text-sm sm:leading-5" placeholder="Suche" autofocus="">
    </div>
    @if (count($items))
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mt-3 mb-3">
            @foreach ($items as $index => $item)
                <li class="col-span-1 flex flex-col justify-between bg-white rounded-lg shadow">

                    <main wire:click="import({{ $index }})" class="flex-grow relative cursor-pointer rounded-t-lg">
                         <img class="rounded-t-lg" src="{{ $item['poster_path'] ? 'https://image.tmdb.org/t/p/w300_and_h450_bestv2' . $item['poster_path'] : Storage::disk('s3')->url('no/680x1000.png') }}">

                         <div class="absolute inset-0 flex items-center justify-center">
                             <button type="button" class="text-xl bg-white text-gray-700 border-gray-300 hover:text-gray-500 inline-flex items-center px-3 py-3 border border-gray-700 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150" title="Importieren">
                                <i wire:loading.class="fa-plus fa-spinner fa-spin" target="import" class="fas fa-plus"></i>
                            </button>
                         </div>
                         <div class="absolute bottom-3 left-3">
                            @if (Arr::has($item, 'first_air_date') && $item['first_air_date'])
                                <div class="flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-yellow-300 text-yellow-800">
                                    {{ (new \Carbon\Carbon($item['first_air_date']))->format('d.m.Y') }}
                                </div>
                            @endif
                         </div>
                    </main>
                    <footer title="{{ $item['name'] }}" class="flex items-center px-3 py-1">
                        <h3 class="flex-grow text-gray-900 leading-5 font-medium overflow-hidden whitespace-nowrap">
                            {{ $item['name'] }}
                        </h3>
                        <div class="ml-1">

                        </div>
                    </footer>
                </li>
            @endforeach
        </ul>
    @endif
</div>