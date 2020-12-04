<div wire:init="loadItems">
    <div class="flex items-center">
        <h2 class="mr-1 font-bold">Nächste Folgen</h2>
        <i wire:loading.delay class="fa fa-spinner fa-spin text-grey"></i>
    </div>
    @if (isset($items))
        <ul class="grid grid-rows-1 grid-cols-6-3/4 sm:grid-cols-6-1/3 md:grid-cols-6-1/4 lg:grid-cols-6 xl:grid-cols-6 gap-6 overflow-auto mb-3">
            @foreach ($items as $item)
                @livewire('media.card', ['model' => $item, 'load_next' => true ], key('next-episodes-' . $item->id . '-' . time()))
            @endforeach
        </ul>

        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-end flex-1">
                @if ($page)
                    <span>
                        <button wire:click="previousPage" dusk="previousPage.before" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </span>
                @endif
                @if ($items->count() == 6)
                    <span>
                        <button wire:click="nextPage" dusk="nextPage.before" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </span>
                @endif
            </div>
        </nav>
    @endif
</div>
