<x-app-layout>
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

</x-app-layout>