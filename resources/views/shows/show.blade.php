<x-app-layout>
    <x-media.hero :model="$model"/>

    <x-media.about :model="$model"/>

    </section>

    <x-container class="py-4">
        <div>
            @foreach($model->seasons as $season)
                @livewire('shows.seasons.index', ['season' => $season], key('shows.seasons.index.' . $season->id))
            @endforeach
        </div>
    </x-container>

</x-app-layout>