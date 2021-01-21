<section class="">
    <x-container>
        <div class="flex">
            <div class="hidden md:block md:w-1/3 lg:w-1/4">
                @if ($model->poster_path)
                    <div class="mr-4 hidden sm:block">
                        <img loading="lazy" class="max-w-full rounded-md" src="{{ Storage::disk('s3')->url('w680' . $model->poster_path) }}" itemprop="image">
                    </div>
                @endif
            </div>
            <div class="w-100 md:w-2/3 lg:w-3/4 flex flex-col">
                <p class="h-24 overflow-auto">
                    @if ($model->overview)
                        {{ $model->overview }}
                    @else
                        Momentan gibt es keine Inhaltsangabe.
                        @if ($model->tmdb_path)
                            Unterstütze uns indem du <a class="text-blue-500 hover:text-blue-600" href="{{ $model->tmdb_path }}" target="_blank">hier</a> eine hinzufügst.
                        @endif
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
                        <div class="w-1/2 md:w-5/6" itemprop="dateCreated" content="{{ $model->released_at }}">{{ is_null($model->released_at) ? 'TBA' : $model->released_at->format('d.m.Y') }}</div>
                    </div>
                @elseif ($model->is_show)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Ausstrahlung</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6" itemprop="dateCreated" content="{{ $model->first_aired_at }}">{{ is_null($model->first_aired_at) ? 'TBA' : $model->first_aired_at->format('d.m.Y') }} bis {{ is_null($model->last_aired_at) ? 'jetzt' : $model->last_aired_at->format('d.m.Y') }} @if ($model->status == 'Canceled') (Abgesetzt) @elseif ($model->status == 'Ended') (Beendet) @endif</div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold"></div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6">@if ($model->air_day) {{ strtolower($model->air_day) }}s @endif @if($model->air_time && $model->air_time != '00:00:00') um {{ (new DateTime($model->air_time))->format('H:i') }} Uhr @endif</div>
                    </div>
                @elseif ($model->is_episode)
                    <div class="flex items-center">
                        <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Ausstrahlung</div>
                        <div class="w-1/2 md:w-3/4 lg:w-5/6" itemprop="dateCreated" content="{{ $model->first_aired_at }}">{{ is_null($model->first_aired_at) ? 'TBA' : $model->first_aired_at->format('d.m.Y') }}</div>
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
                            <div class="w-1/2 md:w-3/4 lg:w-5/6">{!! $model->directors->implode('name_string', ', ') !!}</div>
                        </div>
                    @endif
                    @if ($model->writers->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold">Autor</div>
                            <div class="w-1/2 md:w-3/4 lg:w-5/6">{!! $model->writers->implode('name_string', ', ') !!}</div>
                        </div>
                    @endif
                    @if ($model->actors->count())
                        <div class="flex items-center">
                            <div class="w-1/2 md:w-1/4 lg:w-1/6 font-bold self-start">Schauspieler</div>
                            <div class="w-1/2 md:w-3/4 lg:w-5/6 max-h-16 overflow-auto">{!! $model->actors->implode('name_string', ', ') !!}</div>
                        </div>
                    @endif
                @endunless
                @if ($model->is_show || $model->is_collection)
                    <x-media.progress.index :model="$model"></x-media.progress.index>
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