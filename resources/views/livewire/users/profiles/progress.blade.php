<div>
    <div class="mb-3 pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Fortschritt
        </h3>
        <div class="mt-3 flex sm:mt-0 sm:ml-4">
            <select wire:model="sort_by" class="ml-3 rounded-md border-gray-300">
                <option value="name">Name</option>
                <option value="watched_percent">Fortschritt</option>
                <option value="episodes_left">Folgen nicht gesehen</option>
                <option value="runtime_left">Zeit nicht gesehen</option>
                <option value="tmdb_popularity">Popularität</option>
                <option value="runtime_sum">Laufzeit</option>
                <option value="max_watched_at">Gesehen</option>
            </select>
            <button wire:click="toggleSortDirection" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                @if ($sort_direction == 'ASC')
                    <i class="fas fa-sort-amount-up"></i>
                @elseif($sort_direction == 'DESC')
                    <i class="fas fa-sort-amount-down"></i>
                @endif
            </button>
        </div>
    </div>

    <div wire:loading.delay class="text-center w-100 p-5">
        <i class="fa fa-spinner fa-spin fa-3x text-grey"></i>
    </div>
    <ul class="grid grid-cols-1 gap-6 mb-3">
        @forelse ($items as $item)
            <li>
                <div class="flex">
                    <div class="flex-grow">
                        <h4 class="mb-3 text-base font-bold leading-6 font-medium text-gray-900"><a href="{{ $item->show->path }}" class="hover:underline">{{ $item->show->name }}</a></h4>
                        <x-media.progress.index :model="$item->show"></x-media.progress.index>
                    </div>
                    <div class="px-5 hidden md:block max-w-sm">
                        @if ($item->show->next_episode_to_watch)
                            <h4 class="mb-3 text-base font-bold leading-6 font-medium text-gray-900">Nächste Folge</h4>
                            <ul class="grid grid-rows-1">
                                @livewire('media.card', ['model' => $item->show->next_episode_to_watch, 'type' => 'backdrop', 'load_next' => true ], key('media-card-' . $item->show->next_episode_to_watch))
                            </ul>
                        @endif
                    </div>
                </div>
            </li>
        @empty
           <li class="bg-white rounded-lg shadow px-4 py-2" style="grid-column: 1 / -1;">Noch nichts gesehen.</li>
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
