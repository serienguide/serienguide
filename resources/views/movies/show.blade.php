<x-app-layout>
    <x-media.hero :model="$model"/>

    <section>
        <x-container class="py-4">
            @auth
                @livewire('media.buttons.watch', ['model' => $model])
                <x-media.buttons.listing :model="$model"/>
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