<div>
    <div wire:loading.delay class="text-center w-100 p-5">
        Lade Daten...
    </div>
    <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-3">
        @forelse ($items as $item)
            <li class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow">
                <h3 class="p-6 text-gray-900 leading-5 font-medium"><a href="{{ $item->path }}">{{ $item->title }}</a></h3>
                <div class="text-sm mb-5 text-gray-900">
                    @if($item->watched_count)
                        {{ $item->watched_count }} mal gesehen
                    @endif
                </div>
            </li>
        @empty
            Keine Filme vorhanden
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
