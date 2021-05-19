<x-app-layout :html-attributes="$model->html_attributes" itemtype="http://schema.org/TVSeries">
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">
            @auth
                <buttons-watched-create class="relative inline-block" :model="{{ json_encode($model) }}" :progress="{{ json_encode($model->progress) }}" :is-stand-alone="true"></buttons-watched-create>
                <buttons-lists class="relative inline-block text-left" :model="{{ json_encode($model) }}" :is-stand-alone="true"></buttons-lists>
                <x-media.buttons.tmdb_update :model="$model"/>
                <x-media.buttons.tmdb_edit :model="$model"/>
            @endauth
        </x-container>
    </section>

    <x-media.about :model="$model"/>

    <section class="bg-gray-700 mt-8">
        <x-container class="py-12">
            <ul class="grid grid-rows-1 grid-cols-4-1/1 gap-6 sm:grid-cols-4-1/2 md:grid-cols-4-1/2 lg:grid-cols-4 overflow-auto">
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
                <deck-seasons-index :model="{{ json_encode($season) }}" :is-current="{{ ($season->season_number == $model->current_season_number ? 'true' : 'false') }}"></deck-seasons-index>
            @endforeach
        </div>
    </x-container>

    <section class="bg-gray-100 py-8">
        <x-container class="">
            @livewire('comments.index', ['model' => $model])
        </x-container>
    </section>

    <section class="bg-gray-400 mt-8">
        <x-container class="py-4">
            <div class="">
                @livewire('media.related', ['model' => $model])
            </div>
        </x-container>
    </section>

</x-app-layout>