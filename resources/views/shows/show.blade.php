<x-app-layout>
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">
            @auth
                @livewire('media.buttons.watch', ['model' => $model])
            @endauth
        </x-container>
    </section>

    <x-media.about :model="$model"/>

    <section class="bg-gray-700 mt-8">
        <x-container class="py-12">
            <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @if ($model->next_episode_to_watch)
                    @livewire('media.card', ['model' => $model->next_episode_to_watch, 'type' => 'backdrop', 'load_next' => true ], key('media-card-' . $model->next_episode_to_watch))
                @endif
                @foreach ($model->last_aired_episodes as $key => $last_aired_episode)
                    @livewire('media.card', ['model' => $last_aired_episode, 'type' => 'backdrop', 'load_next' => false ], key('media-card-' . $last_aired_episode))
                @endforeach
            </ul>
        </x-container>
    </section>

    <x-container class="py-4">
        <div>
            @foreach($model->seasons as $season)
                @livewire('shows.seasons.index', ['season' => $season, 'isCurrent' => ($season->season_number == $model->current_season_number)], key('shows.seasons.index.' . $season->id))
            @endforeach
        </div>
    </x-container>

</x-app-layout>