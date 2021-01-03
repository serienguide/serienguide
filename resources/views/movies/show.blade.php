<x-app-layout :html-attributes="$model->html_attributes" itemtype="http://schema.org/Movie">
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">
            @auth
                <div class="inline-block px-1">
                    @livewire('media.buttons.watch', ['model' => $model])
                </div>
                <x-media.buttons.listing :model="$model"/>
                <x-media.buttons.tmdb_update :model="$model"/>
                <x-media.buttons.tmdb_edit :model="$model"/>
            @endauth
        </x-container>
    </section>

    <x-media.about :model="$model"/>

    @if($model->collection)
        <section class="bg-gray-700 mt-8">
            <x-container class="py-12">
                <a href="{{ $model->collection->path }}">
                    <div class="relative rounded-md text-white bg-gray-700 " style="background-image: url({{ $model->backdrop_url_xl }}); height: 150px; background-position: 50% 25%;">
                        <div class="absolute left-0 right-0 bottom-0 h-36 rounded-md" style="background-image: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.6) 100%);"></div>
                        <div class="absolute left-0 right-0 bottom-0 py-4 px-8 whitespace-nowrap rounded-md">
                            <h2 class="text-xl font-bold">{{ $model->collection->name }}</h2>
                        </div>
                    </div>
                </a>
            </x-container>
        </section>
    @endif

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

    <section class="bg-gray-400 mt-8">
        <x-container class="py-4">
            <div class="">
                @livewire('media.related', ['model' => $model])
            </div>
        </x-container>
    </section>

</x-app-layout>