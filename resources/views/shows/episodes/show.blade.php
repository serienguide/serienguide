<x-app-layout :html-attributes="$model->html_attributes" itemtype="http://schema.org/TVEpisode">
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">
            @auth
                <div class="inline-block px-1">
                    @livewire('media.buttons.watch', ['model' => $model])
                </div>
                <x-media.buttons.tmdb_update :model="$model"/>
                <x-media.buttons.tmdb_edit :model="$model"/>
            @endauth
        </x-container>
    </section>

    <x-media.about :model="$model"/>

    @guest
        <section class="bg-gray-700 mt-8"></section>
    @endguest

    @auth
        <x-container class="py-4">
            <div class="">
                @livewire('watched.ul', ['model' => $model])
            </div>
        </x-container>
    @endauth

    @if ($next_episode)
        <div class="fixed opacity-25 hover:opacity-100" style="top:35%; right: 25px;">
            <a href="{{ $next_episode->path }}" title="{{ $next_episode->season->season_number }}x{{ $next_episode->episode_number }} {{ $next_episode->name }}">
                <i class="fas fa-chevron-right fa-3x"></i>
            </a>
        </div>
    @endif

    <section class="bg-gray-100 py-8">
        <x-container class="">
            @livewire('comments.index', ['model' => $model])
        </x-container>
    </section>

    @if ($previous_episode)
        <div class="fixed opacity-25 hover:opacity-100" style="top:35%; left: 25px;">
            <a href="{{ $previous_episode->path }}" title="{{ $previous_episode->season->season_number }}x{{ $previous_episode->episode_number }} {{ $previous_episode->name }}">
                <i class="fas fa-chevron-left fa-3x"></i>
            </a>
        </div>
    @endif

</x-app-layout>