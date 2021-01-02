<div wire:init="loadItems">
    <div class="flex items-baseline">
        <h2 class="mr-1 font-bold text-xl mb-3">Das könnte dir auch gefallen</h2>
        <i wire:loading.delay class="fa fa-spinner fa-spin text-grey"></i>
    </div>
    @if (isset($items))
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
            @foreach ($items as $item)
                @livewire('media.card', ['model' => $item], key('media-card-' . $item->id))
            @endforeach
        </ul>
    @endif
</div>
