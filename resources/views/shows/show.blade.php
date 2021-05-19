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

            <deck-shows-last-aired-episodes :next-episode="{{ json_encode($model->next_episode_to_watch) }}" :episodes="{{ json_encode($model->last_aired_episodes) }}"></deck-shows-last-aired-episodes>

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
                <deck-media-related :model="{{ json_encode($model) }}"></deck-media-related>
            </div>
        </x-container>
    </section>

</x-app-layout>