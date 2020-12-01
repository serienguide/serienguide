<div wire:loading.delay class="text-center w-100 p-5">
    <i class="fa fa-spinner fa-spin fa-3x text-grey"></i>
</div>
<ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
    @forelse ($items as $item)
        @livewire('media.card', ['model' => $item, 'load_next' => $loadNext ?? false ], key('media-card-' . $item->id))
    @empty
        Keine Filme vorhanden
    @endforelse
</ul>

{{ $items->links() }}