<x-app-layout>
    <x-media.hero :model="$model"/>

    <x-media.about :model="$model"/>

    <section class="bg-gray-700">
        <x-container class="py-12">
            <div class="flex">
                <ul class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @if ($model->next_episode_to_watch)
                        @livewire('media.card', ['model' => $model->next_episode_to_watch, 'type' => 'backdrop', 'load_next' => true ], key('media-card-' . $model->next_episode_to_watch))
                    @endif
                    @foreach ($model->last_aired_episodes as $last_aired_episode)
                        @livewire('media.card', ['model' => $last_aired_episode, 'type' => 'backdrop', 'load_next' => false ], key('media-card-' . $last_aired_episode))
                    @endforeach
                </ul>
            </div>
        </x-container>
    </section>

    </section>

    <x-container class="py-4">
        <div>
            @foreach($model->seasons as $season)
                @livewire('shows.seasons.index', ['season' => $season], key('shows.seasons.index.' . $season->id))
            @endforeach
        </div>
    </x-container>

</x-app-layout>