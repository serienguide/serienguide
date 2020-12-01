<x-app-layout>
    <x-media.hero :model="$model"/>

    <section class="py-5">
        <x-container>
            <div class="flex">
                <div class="hidden md:block md:w-1/3 lg:w-1/4">
                    @if ($model->poster_path)
                        <div class="mr-4 hidden sm:block">
                            <img class="max-w-full" src="{{ Storage::disk('s3')->url('w680' . $model->poster_path) }}">
                        </div>
                    @endif
                </div>
                <div class="w-100 md:w-2/3 lg:w-3/4 flex flex-col">
                    <p class="max-h-36 overflow-auto">
                        {{ ($model->overview ?: 'Momentan gibt es keine Inhaltsangabe. Unterstütze uns indem du <a href="https://www.themoviedb.org/movie/' . $model->tmdb_id . '" target="_blank">hier</a> eine hinzufügst.') }}
                    </p>

                    @if ($model->genres->count())
                        <div class="my-2">{!! $model->genres->implode('badge', ' ') !!}</div>
                    @endif

                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/6 font-bold">Release</div>
                        <div class="w-1/2 md:w-5/6">{{ $model->released_at->format('d.m.Y') }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/6 font-bold">Dauer</div>
                        <div class="w-1/2 md:w-5/6">{{ $model->runtime }} Minuten</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/6 font-bold">Budget</div>
                        <div class="w-1/2 md:w-5/6">${{ number_format($model->budget, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/6 font-bold">Eingespielt</div>
                        <div class="w-1/2 md:w-5/6">${{ number_format($model->revenue, 0, ',', '.') }}</div>
                    </div>
                    @if ($model->directors->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/6 font-bold">Regisseur</div>
                            <div class="w-1/2 md:w-5/6">{{ $model->directors->implode('person.name', ', ') }}</div>
                        </div>
                    @endif
                    @if ($model->writers->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/6 font-bold">Autor</div>
                            <div class="w-1/2 md:w-5/6">{{ $model->writers->implode('person.name', ', ') }}</div>
                        </div>
                    @endif

                    @if (false)
                        <div class="my-3 rounded h-4 w-full bg-blue-900" title="{{ $model->progress['watched_count'] }}/{{ $model->progress['watchable_count'] }} {{ $model->progress['percent'] }}%">
                            <div class="bg-blue-500 h-4 text-xs leading-none text-center text-white @if ($model->progress['percent'] > 0) rounded-l @endif @if ($model->progress['percent'] == 100) rounded-r @endif" style="width: {{ $model->progress['percent'] }}%"></div>
                        </div>
                    @endif
                    <div class="flex-grow"></div>
                    <div class="my-3">
                        @livewire('media.rating', ['model' => $model ], key('media-rating-' . $model->id))
                    </div>

                </div>
            </div>
        </x-container>
    </section>

    @auth
        <x-container>
            <div class="mt-3">
                @livewire('watched.ul', ['model' => $model])
            </div>
        </x-container>
    @endauth

</x-app-layout>