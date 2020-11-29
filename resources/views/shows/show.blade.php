<x-app-layout>
    <x-slot name="header">
        <a href="{{ $model->index_path }}">Serien</a> > {{ $model->name }}
    </x-slot>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="flex px-4 py-5 sm:px-6">
            @if ($model->poster_path)
                <div class="px-4 hidden sm:block">
                    <img style="max-width: 100%;" src="{{ Storage::disk('s3')->url('w680' . $model->poster_path) }}">
                </div>
            @endif
            <dl class="flex-grow grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">
                        Inhalt
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $model->overview }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Genres
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $model->genres->implode('name', ', ') }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Ausstrahlung
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $model->first_aired_at->format('d.m.Y') }} bis {{ is_null($model->last_aired_at) ? 'jetzt' : $model->last_aired_at->format('d.m.Y') }}
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Folgen
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $model->episodes_count }} Folgen in {{ $model->seasons_count }} Staffeln
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">
                        Streams
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <a href="https://www.themoviedb.org/tv/{{ $model->tmdb_id }}/watch?locale=DE">{{ $model->providers->implode('name', ', ') }}</a>
                    </dd>
                </div>
            </div>
        </dl>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        @foreach($model->seasons as $season)
            @livewire('shows.seasons.index', ['season' => $season], key('shows.seasons.index.' . $season->id))
        @endforeach
    </div>
</div>

</x-app-layout>