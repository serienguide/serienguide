<x-app-layout>
    <x-media.hero :model="$model"/>

    <x-media.about :model="$model"/>

    @auth
        <x-container>
            <div class="mt-3">
                @livewire('watched.ul', ['model' => $model])
            </div>
        </x-container>
    @endauth

</x-app-layout>