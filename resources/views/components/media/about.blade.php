<section class="">
    <x-container>
        <div class="flex">
            <div class="hidden md:block md:w-1/3 lg:w-1/4">
                @if ($model->poster_path)
                    <div class="mr-4 hidden sm:block">
                        <img class="max-w-full rounded-md" src="{{ Storage::disk('s3')->url('w680' . $model->poster_path) }}">
                    </div>
                @endif
            </div>
            <div class="w-100 md:w-2/3 lg:w-3/4 flex flex-col">
                <p class="max-h-24 overflow-auto">
                    @if ($model->overview)
                        {{ $model->overview }}
                    @else
                        'Momentan gibt es keine Inhaltsangabe. Unterstütze uns indem du <a href="https://www.themoviedb.org/movie/{{ $model->tmdb_id }}" target="_blank">hier</a> eine hinzufügst.
                    @endif
                </p>

                @if ($model->is_movie && $model->is_show)
                    @if ($model->genres->count())
                        <div class="my-2">{!! $model->genres->implode('badge', ' ') !!}</div>
                    @endif
                @endif

                @if ($model->is_movie)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/6 font-bold">Release</div>
                        <div class="w-1/2 md:w-5/6">{{ is_null($model->released_at) ? 'TBA' : $model->released_at->format('d.m.Y') }}</div>
                    </div>
                @elseif ($model->is_show)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Ausstrahlung</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">{{ is_null($model->first_aired_at) ? 'TBA' : $model->first_aired_at->format('d.m.Y') }} bis {{ is_null($model->last_aired_at) ? 'jetzt' : $model->last_aired_at->format('d.m.Y') }} @if ($model->status == 'Canceled') (Abgesetzt) @elseif ($model->status == 'Ended') (Beendet) @endif</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold"></div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">@if ($model->air_day) {{ strtolower($model->air_day) }}s @endif @if($model->air_time && $model->air_time != '00:00:00') um {{ (new DateTime($model->air_time))->format('H:i') }} Uhr @endif</div>
                    </div>
                @elseif ($model->is_collection)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Filme</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">{{ $model->movies->count() }}</div>
                    </div>
                @endif
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Dauer</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">{{ $model->runtime }} Minuten @if ($model->is_show || $model->is_collection) ({{ round($model->progress['watchable_runtime'] / 60, 0) }}h {{ $model->progress['watchable_runtime'] % 60 }}m) @endif</div>
                    </div>
                @if ($model->is_movie || $model->is_collection)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Budget</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">${{ number_format($model->budget, 0, ',', '.') }}</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Eingespielt</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">${{ number_format($model->revenue, 0, ',', '.') }}</div>
                    </div>
                @endif
                @unless($model->is_collection)
                    @if ($model->directors->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Regisseur</div>
                            <div class="w-1/2 md:w-3/4 lg:w-5/6">{{ $model->directors->implode('person.name', ', ') }}</div>
                        </div>
                    @endif
                    @if ($model->writers->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Autor</div>
                            <div class="w-1/2 md:w-3/4 lg:w-5/6">{{ $model->writers->implode('person.name', ', ') }}</div>
                        </div>
                    @endif
                    @if ($model->actors->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold self-start">Schauspieler</div>
                            <div class="w-1/2 md:w-3/4 lg:w-5/6 max-h-16 overflow-auto">{{ $model->actors->implode('person.name', ', ') }}</div>
                        </div>
                    @endif
                @endunless
                @if ($model->is_show || $model->is_collection)
                    <div class="my-3 rounded h-4 w-full bg-blue-900" title="{{ $model->progress['watched_count'] }}/{{ $model->progress['watchable_count'] }} {{ $model->progress['percent'] }}%">
                        <div class="bg-blue-500 h-4 text-xs leading-none text-center text-white @if ($model->progress['percent'] > 0) rounded-l @endif @if ($model->progress['percent'] == 100) rounded-r @endif" style="width: {{ $model->progress['percent'] }}%"></div>
                    </div>
                        <div>
                            <span class="font-bold">{{ $model->progress['watched_count'] }}</span>
                            von <span class="font-bold">{{ $model->progress['watchable_count'] }}</span>
                            (<span class="font-bold">{{ round($model->progress['watched_runtime'] / 60, 0) }}h
                            {{ $model->progress['watched_runtime'] % 60 }}m</span>)
                            {{ $model->progress['labels']['plural'] }} gesehen.
                            Es {{ ($model->progress['unwatched_count'] == 1 ? ' ist' : 'sind') }}
                            noch <span class="font-bold">{{ $model->progress['unwatched_count'] }}</span>
                            {{ ($model->progress['unwatched_count'] == 1 ? $model->progress['labels']['singular'] : $model->progress['labels']['plural']) }}
                            übrig (
                            <span class="font-bold">{{ round($model->progress['unwatched_runtime'] / 60, 0) }}h {{ $model->progress['unwatched_runtime'] % 60 }}m</span>).
                        </div>
                        @if ($model->last_watched)
                            <div>
                                Zuletzt gesehen
                                @if ($model->is_collection)
                                    <a class="text-blue-500 hover:text-blue-600" href="{{ $model->last_watched->watchable->path }}">{{ $model->last_watched->watchable->name }}</a>
                                @elseif ($model->is_show)
                                    <a class="text-blue-500 hover:text-blue-600" href="{{ $model->last_watched->watchable->path }}">{{ $model->last_watched->watchable->season->season_number }}x{{ $model->last_watched->watchable->episode_number }} {{ $model->last_watched->watchable->name }}</a>
                                @endif
                                {{ $model->last_watched->watched_at->diffForHumans() }} am {{ $model->last_watched->watched_at->format('d') }}. {{ $model->last_watched->watched_at->monthName }} {{ $model->last_watched->watched_at->format('Y (H:i)') }}
                            </div>
                        @endif
                @endif
                <div class="flex-grow"></div>
                @unless($model->is_collection)
                    <div class="my-3">
                        @livewire('media.rating', ['model' => $model ], key('media-rating-' . $model->id))
                    </div>
                @endunless
            </div>
        </div>
    </x-container>
</section>