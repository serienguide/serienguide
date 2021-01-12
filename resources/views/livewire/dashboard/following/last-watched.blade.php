<div>
    <div class="flex items-center">
        <h3 class="mb-3 text-lg font-bold leading-6 font-medium text-gray-900">Aktionen von Freunden</h3>
        <i wire:loading.delay class="ml-1 fa fa-spinner fa-spin text-grey"></i>
    </div>
    <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
        @forelse ($items as $item)
            @livewire('media.card', ['action' => $item, 'model' => $item->watchable], key('media-card-' . $item->id))
        @empty
            Du folgst noch niemandem
        @endforelse
    </ul>

    {{ $items->links() }}
</div>
