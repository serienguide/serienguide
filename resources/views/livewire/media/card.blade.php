<li class="relative col-span-1 flex flex-col justify-between bg-white rounded-lg shadow" @isset($itemtype) itemtype="{{ $itemtype }}" itemscope="" @endisset>
    <div class="rounded-t-lg h-2 w-full bg-yellow-900" title="{{ number_format($model->vote_average, (in_array($model->vote_average, [0, 10]) ? 0 : 1) , ',', '') }}/10 {{ $model->vote_count }} Stimmen" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="bestRating" content="10">
        <meta itemprop="worstRating" content="0">
        <meta itemprop="ratingValue" content="{{ $model->vote_average }}">
        <meta itemprop="ratingCount" content="{{ $model->vote_count }}">

        <div class="bg-yellow-400 h-2 text-xs leading-none text-center text-white transition-all @if($model->vote_average > 0) rounded-tl-lg @endif @if($model->vote_average == 10) rounded-tr-lg @endif" style="width: {{ $model->vote_average * 10 }}%"></div>
    </div>
    @auth
        <header class="flex items-center justify-center px-3 py-1">
            <div class="flex-grow"></div>
            @unless($model->is_episode)
                <div class="inline-block text-left" x-data="{ open: false }">
                    <div class="px-1">
                        <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>

                    <div x-show="open"
                        x-cloak
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute mt-2 rounded-md shadow-lg z-10"
                        style="width: 90%; right: 5%;">
                        <div class="rounded-md bg-white shadow-xs">
                            <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                @livewire('media.listing', ['model' => $model])
                            </div>
                        </div>
                    </div>
                </div>
            @endunless

            <div class="relative inline-block text-left" x-data="{ open: false }">
                <div class="px-1">
                    <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                        <i class="fas fa-star {{ is_null($model->rating_by_user) ? '' : 'text-yellow-400' }}"></i>
                    </button>
                </div>

                <div x-show="open"
                    x-cloak
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg z-10">
                    <div class="rounded-md bg-white shadow-xs">
                        <div class="flex justify-between p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu" x-data="{ rating: {{ is_null($model->rating_by_user) ? 0 : $model->rating_by_user->rating }}, hovered: {{ is_null($model->rating_by_user) ? 0 : $model->rating_by_user->rating }} }" @mouseleave="hovered = rating > 0 ? rating : 0">
                            @if (! is_null($model->rating_by_user))
                                <i wire:click="rate(0)" class="fas fa-trash-alt px-1 cursor-pointer text-red-500" @mouseenter="hovered = 0"></i>
                            @endif
                            @for ($i = 1; $i <= 10; $i++)
                                <i wire:click="rate({{ $i }})" data-rating="{{ $i }}" class="fas fa-star px-1 cursor-pointer" :class="{ 'text-yellow-400': hovered >= {{ $i }}}" @mouseenter="hovered = {{ $i }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>

            @unless($model->is_show)
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <div class="px-1">
                        <button @click="open = true" class="flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600" aria-label="Options" id="options-menu" aria-haspopup="true" aria-expanded="true">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open"
                        x-cloak
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-2 rounded-md shadow-lg z-10">
                        <div class="rounded-md bg-white shadow-xs" style="min-width: 250px;">
                            <div class="p-3" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                @livewire('watched.ul', ['model' => $model, 'action' => $action])
                            </div>
                        </div>
                    </div>
                </div>
            @endunless

        </header>
    @endauth
    <main class="flex-grow relative">
        <a class="" href="{{ (($model->is_episode) ? $model->show->path : $model->path) }}" title="{{ $model->name }}">
            <img loading="lazy" src="{{ $type == 'poster' ? $model->poster_url : $model->backdrop_url }}" itemprop="image">
                @isset($action)
                    <div class="absolute left-0 right-0 bottom-0 h-24" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
                    @if (get_class($action) == App\Models\Watched\Watched::class)
                        <div class="absolute bottom-8 left-0 right-0 inline-flex items-center justify-center">
                            <a class="inline-flex items-center" href="{{ $action->user->profile_path }}" title="{{ $action->user->name }} hat {{ $model->name }} {{ $action->watched_at->diffForHumans() }} am {{ $action->watched_at->format('d.m.Y H:i') }} gesehen">
                                <img class="h-8 w-8 rounded-full ring-2 ring-white" src="https://www.gravatar.com/avatar/{{ md5($action->user->email) }}" alt="">
                                <div class="-ml-2 inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-white" ><i class="fas fa-check"></i></div>
                            </a>
                        </div>
                    @elseif (get_class($action) == App\Models\Ratings\Rating::class)
                        <div class="absolute bottom-8 left-0 right-0 inline-flex items-center justify-center">
                            <a class="inline-flex items-center" href="{{ $action->user->profile_path }}" title="{{ $action->user->name }} hat {{ $model->name }} {{ $action->created_at->diffForHumans() }} am {{ $action->created_at->format('d.m.Y H:i') }} mit {{ $action->rating }} Punkten bewertet">
                                <img class="h-8 w-8 rounded-full ring-2 ring-white" src="https://www.gravatar.com/avatar/{{ md5($action->user->email) }}" alt="">
                                <div class="-ml-2 inline-flex items-center justify-center h-8 w-8 rounded-full ring-2 ring-white bg-white" ><i class="fas fa-star text-yellow-400"></i></div>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="absolute bottom-3 left-3">
                    @if ($model->is_episode)
                        @if ($model->episode_number == 1)
                            @if ($model->season->season_number == 1)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-red-500 text-white">
                                    Serienstart
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-green-500 text-white">
                                    Staffelstart
                                </span>
                            @endif
                        @endif
                        @if ($model->first_aired_at)
                            <div class="flex items-center mt-1 px-3 py-0.5 rounded-full text-xs font-bold @if(is_null($model->first_aired_de_at)) bg-blue-100 text-blue-800 @else bg-yellow-300 text-yellow-800 @endif">
                                {{ $model->first_aired_at->format('d.m.Y') }} @if(! is_null($model->first_aired_de_at) && $model->show->air_time) {{ substr($model->show->air_time, 0, -3) }} @endif
                            </div>
                        @endif
                    @elseif ($model->is_movie)
                        @if ($model->released_at)
                            <div class="flex items-center px-3 py-0.5 rounded-full text-xs font-bold bg-yellow-300 text-yellow-800">
                                {{ $model->released_at->format('d.m.Y') }}
                            </div>
                        @endif
                    @endif
                </div>
            @endisset
        </a>
    </main>
    <footer class="flex items-center px-3 py-1">
        <h3 class="flex-grow text-gray-900 leading-5 font-medium overflow-hidden whitespace-nowrap">
            @if ($model->is_episode)
                <a href="{{ $model->path }}" title="{{ $model->name }}" class="text-center" itemprop="url">
                    <div class="font-bold"><span itemprop='partOfSeason'>{{ $model->season->season_number }}</span>x<span itemprop='episodeNumber'>{{ $model->episode_number }}</span></div>
                    <div class="text-gray-400" itemprop="name">{{ $model->name }}&nbsp;</div>
                </a>
            @elseif ($next_episode)
                <a href="{{ $next_episode->path }}" title="{{ $next_episode->name }}" class="text-center">
                    <div class="font-bold">{{ $next_episode->season->season_number }}x{{ $next_episode->episode_number }}</div>
                    <div class="text-xs text-gray-400">NÄCHSTE EPISODE</div>
                </a>
            @else
                <a href="{{ $model->path }}" title="{{ $model->name }}" itemprop="url">
                    <span itemprop="name">{{ $model->name }}</span>
                </a>
            @endif
        </h3>
        @auth
            <div class="ml-1">
                <button wire:click="watch" type="button" class="inline-flex items-center px-3 py-3 border border-gray-300 text-sm leading-5 font-medium rounded-full whitespace-no-wrap focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150 {{ $button_class }}" title="@if($model->watched_count) {{ $model->watched_count }} mal gesehen @endif">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        @endauth
    </footer>
    <div class="rounded-b-lg h-2 w-full bg-blue-900" title="{{ $model->progress['watched_count'] }}/{{ $model->progress['watchable_count'] }} {{ $model->progress['percent'] }}%">
        <div class="bg-blue-500 h-2 text-xs leading-none text-center text-white transition-all duration-500 @if($model->progress['percent'] > 0) rounded-bl-lg @endif @if($model->progress['percent'] == 100) rounded-br-lg @endif" style="width: {{ $model->progress['percent'] }}%"></div>
    </div>
</li>