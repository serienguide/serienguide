<x-app-layout>

    <x-slot name="header">
        Filme
    </x-slot>

    <x-container class="py-4">

        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
            @forelse ($episodes as $movie)
                <card-show :model="{{ json_encode($movie->toCard()) }}" :load-next="true"></card-show>
            @empty
                Keine Filme vorhanden
            @endforelse
        </ul>

        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
            @forelse ($shows as $movie)
                <card-show :model="{{ json_encode($movie->toCard()) }}"></card-show>
            @empty
                Keine Filme vorhanden
            @endforelse
        </ul>

        <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 mb-3">
            @forelse ($movies as $movie)
                <card-show :model="{{ json_encode($movie->toCard()) }}"></card-show>
            @empty
                Keine Filme vorhanden
            @endforelse
        </ul>

    </x-container>

</x-app-layout>