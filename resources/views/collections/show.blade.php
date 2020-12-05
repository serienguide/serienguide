<x-app-layout>
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">

        </x-container>
    </section>

    <x-media.about :model="$model"/>

    <section class="bg-gray-700 mt-8"></section>

    <x-container class="py-4">
        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
            @forelse ($model->movies as $item)
                @livewire('media.card', ['model' => $item], key('media-card-' . $item->id))
            @empty
                Keine Filme vorhanden
            @endforelse
        </ul>
    </div>
    </x-container>

</x-app-layout>