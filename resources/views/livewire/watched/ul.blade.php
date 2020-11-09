<div>
    <span class="inline-flex rounded-md shadow-sm">
        <button wire:click="watch" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
            Gesehen
        </button>
    </span>
    <ul class="divide-y divide-gray-200">
        @forelse ($items as $item)
            <li class="py-4 space-x-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm leading-5 font-medium text-indigo-600 truncate">
                        {{ $item->watched_at->format('d.m.Y H:i') }}
                        <div class="text-sm text-gray-500">Erstellt: {{ $item->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    <div class="ml-2 flex-shrink-0 flex">
                        <span wire:click="destroy({{ $loop->index }})" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 cursor-pointer">
                            löschen
                        </span>
                    </div>
                </div>
            </li>
        @empty
            <li class="py-4 flex space-x-3">Noch nicht gesehen</li>
        @endforelse
</div>
